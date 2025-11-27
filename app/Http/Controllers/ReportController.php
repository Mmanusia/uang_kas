<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\MonthlyIncome;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function monthly(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12'
        ]);

        $userId = Auth::id();

        // Ambil monthly income
        $income = MonthlyIncome::where('user_id', $userId)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->first();

        // Ambil semua budget groups (living, playing, saving)
        $budgets = Budget::with('groups')
            ->where('user_id', $userId)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->get();

        $budgetMap = [];

        foreach ($budgets as $budget) {
            $budgetMap[$budget->groups->name] = [
                'limit' => $budget->limit_amount,
                'percentage' => $budget->limit_percentage
            ];
        }

        // Ambil semua kategori yang type = expense untuk groups
        $expenseCategories = Category::where('type', 'expense')->pluck('id');

        // Hitung pengeluaran per kategori
        $expenses = Transaction::where('user_id', $userId)
            ->whereIn('category_id', $expenseCategories)
            ->whereYear('date', $request->year)
            ->whereMonth('date', $request->month)
            ->with('category.groups')
            ->get();

        // Kelompokkan total pengeluaran per GROUP
        $expenseByGroup = [];

        foreach ($expenses as $trx) {
            $gName = $trx->category->groups->name;

            if (!isset($expenseByGroup[$gName])) {
                $expenseByGroup[$gName] = 0;
            }

            $expenseByGroup[$gName] += $trx->amount;
        }

        // Hitung sisa budget masing² group
        $report = [];

        foreach ($budgetMap as $groups => $b) {
            $spent = $expenseByGroup[$groups] ?? 0;
            $remaining = $b['limit'] - $spent;

            $report[$groups] = [
                'limit' => $b['limit'],
                'percentage' => $b['percentage'],
                'spent' => $spent,
                'remaining' => $remaining,
                'status' => $remaining < 0 ? 'overbudget' : 'ok'
            ];
        }

        return response()->json([
            'month' => $request->month,
            'year' => $request->year,
            'income_main' => $income->amount ?? 0,
            'budget_summary' => $report
        ]);
    }
}
