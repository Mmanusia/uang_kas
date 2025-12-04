<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'month' => 'required'
        ]);

        $budgets = Budget::with('groups')
            ->where('user_id', Auth::id())
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->get();

        return response()->json($budgets);
    }

    public function updatePercentages(Request $request)
    {
        $request->validate([
            'living' => 'required|integer|min:0|max:100',
            'playing' => 'required|integer|min:0|max:100',
            'saving' => 'required|integer|min:0|max:100',
        ]);

        $total = $request->living + $request->playing + $request->saving;
        if ($total !== 100) {
            return response()->json(['success' => false, 'message' => 'Total persentase harus 100%']);
        }

        $userId = Auth::id();
        $month = now()->month;
        $year = now()->year;

        // Get current income
        $income = \App\Models\MonthlyIncome::where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->sum('amount');

        // If no income for current month, get latest
        if ($income == 0) {
            $latestIncome = \App\Models\MonthlyIncome::where('user_id', $userId)
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->first();
            $income = $latestIncome ? $latestIncome->amount : 0;
        }

        // Update living (group_id = 1)
        $livingAmount = ($request->living / 100) * $income;
        Budget::where('user_id', $userId)
            ->where('group_id', 1)
            ->where('month', $month)
            ->where('year', $year)
            ->update([
                'limit_percentage' => $request->living,
                'limit_amount' => $livingAmount
            ]);

        // Update playing (group_id = 2)
        $playingAmount = ($request->playing / 100) * $income;
        Budget::where('user_id', $userId)
            ->where('group_id', 2)
            ->where('month', $month)
            ->where('year', $year)
            ->update([
                'limit_percentage' => $request->playing,
                'limit_amount' => $playingAmount
            ]);

        // Update saving (group_id = 3)
        $savingAmount = ($request->saving / 100) * $income;
        Budget::where('user_id', $userId)
            ->where('group_id', 3)
            ->where('month', $month)
            ->where('year', $year)
            ->update([
                'limit_percentage' => $request->saving,
                'limit_amount' => $savingAmount
            ]);

        return response()->json(['success' => true]);
    }
}
