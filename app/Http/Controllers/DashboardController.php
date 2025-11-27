<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\MonthlyIncome;
use App\Models\Transaction;
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

        return view('dashboard', compact(
            'budgets',
            'income',
            'expense',
            'balance',
            'latest'
        ));
    }
}
