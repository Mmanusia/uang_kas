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
}
