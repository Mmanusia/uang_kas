<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\TransactionController;

class TransactionUIController extends Controller
{
    public function index() {
        $categories = Category::all();
        return view('transaction.index', compact('categories'));
    }

    public function store(Request $request, TransactionController $api) {
        $api->store($request);
        return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan');
    }
}
