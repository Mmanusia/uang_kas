<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\MonthlyIncome;
use App\Models\Transaction;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $month = now()->month;
        $year = now()->year;

        // Ambil budget bulan ini
        $budgets = Budget::with('group')
            ->whereHas('group')
            ->where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        // Hitung total pemasukan bulan ini dari monthly_incomes
        $income = MonthlyIncome::where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->sum('amount');

        // Jika tidak ada data untuk bulan ini, ambil dari bulan terakhir yang ada
        if ($income == 0) {
            $latestIncome = MonthlyIncome::where('user_id', $user->id)
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->first();
            if ($latestIncome) {
                $income = $latestIncome->amount;
            }
        }

        // Hitung total pengeluaran
        $expense = Transaction::where('user_id', $user->id)
            ->whereHas('category', function ($q) {
                $q->where('type', 'expense');
            })
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        // Sisa saldo
        $balance = $income - $expense;

        // Transaksi terbaru
        $latest = Transaction::with('category')
            ->where('user_id', $user->id)
            ->latest()
            ->take(7)
            ->get();

        // Ambil persentase saat ini untuk modal
        $currentPercentages = [
            'living' => 0,
            'playing' => 0,
            'saving' => 0
        ];

        foreach ($budgets as $budget) {
            $groupName = strtolower($budget->group->name);
            if (isset($currentPercentages[$groupName])) {
                $currentPercentages[$groupName] = $budget->limit_percentage ?? 0;
            }
        }

        // Ambil kategori untuk modal transaksi
        $categories = Category::with('groups')->get();

        return view('dashboard', compact(
            'budgets',
            'income',
            'expense',
            'balance',
            'latest',
            'currentPercentages',
            'categories'
        ));
    }
}
