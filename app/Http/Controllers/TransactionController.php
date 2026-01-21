<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Groups;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'description' => 'nullable|string'
        ]);

        // Ambil kategori dengan group
        $category = Category::with('groups')->find($request->category_id);

        $groups = Groups::where('id', $category->group_id)->first();
        $groupName = $groups ? $groups->name : 'kategori ini';

        // Tentukan bulan dan tahun dari tanggal transaksi
        $currentMonth = date('m', strtotime($request->date));
        $currentYear = date('Y', strtotime($request->date));

        // Cari budget untuk group tersebut di bulan ini
        $budget = Budget::where('user_id', Auth::id())
            ->where('group_id', $category->group_id)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        if ($budget) {
            // Hitung pengeluaran saat ini untuk group ini di bulan ini
            $currentSpending = Transaction::where('user_id', Auth::id())
                ->whereHas('category', function($q) use ($category) {
                    $q->where('group_id', $category->group_id);
                })
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->sum('amount');

            // Cek apakah penambahan transaksi ini akan melebihi budget
            if ($currentSpending + $request->amount > $budget->limit_amount) {
                $groupName = $category->groups ? $category->groups->name : 'kategori ini';
                return redirect('/dashboard')->with('error', 'Tidak dapat menambah pengeluaran. Budget untuk ' . $groupName . ' sudah mencapai batas maksimal.');
            }
        }

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'date' => $request->date,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return redirect('/dashboard')->with('success', 'Transaksi berhasil ditambahkan!');
    }
}
