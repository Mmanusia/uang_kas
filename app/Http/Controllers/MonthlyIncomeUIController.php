<?php

namespace App\Http\Controllers;

use App\Models\MonthlyIncome;
use App\Models\Budget;
use App\Models\Groups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonthlyIncomeUIController extends Controller
{
    public function index() {
        return view('income.index');
    }

    public function store(Request $request) {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'amount' => 'required|numeric|min:0'
        ]);

        $userId = Auth::id();

        // Create or update monthly income
        $income = MonthlyIncome::updateOrCreate(
            [
                'user_id' => $userId,
                'year' => $request->year,
                'month' => $request->month,
            ],
            [
                'amount' => $request->amount
            ]
        );

        // Get groups: living, playing, saving
        $groups = Groups::all(); // living = 1, playing = 2, saving = 3

        $percentages = [
            'living' => 50,
            'playing' => 30,
        ];

        // saving = sisa
        $percentages['saving'] = 100 - ($percentages['living'] + $percentages['playing']);

        foreach ($groups as $g) {
            $p = $percentages[strtolower($g->name)] ?? 0;
            $limit = $income->amount * ($p / 100);

            Budget::updateOrCreate(
                [
                    'user_id' => $userId,
                    'group_id' => $g->id,
                    'month' => $request->month,
                    'year' => $request->year,
                ],
                [
                    'limit_percentage' => $p,
                    'limit_amount' => $limit,
                ]
            );
        }

        return redirect()->route('dashboard')->with('success', 'Income Berhasil Di Tambahkan');
    }
}
