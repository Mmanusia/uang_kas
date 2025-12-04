<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\TransactionController;

class TransactionUIController extends Controller
{
    public function index() {
        $categories = Category::with('group')->get();
        return view('transaction.index', compact('categories'));
    }

    public function store(Request $request, TransactionController $api) {
        $response = $api->store($request);

        // Jika response adalah JSON error (budget exceeded), redirect dengan error
        if ($response->getStatusCode() !== 200) {
            $errorData = json_decode($response->getContent(), true);
            return redirect()->back()->with('error', $errorData['message']);
        }

        return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan');
    }
}
