<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\MonthlyIncomeController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonthlyIncomeUIController;
use App\Http\Controllers\ReportUIController;
use App\Http\Controllers\TransactionUIController;

use App\Http\Middleware\AuthMiddleware;

Route::get('/', function () {
    return view('welcome');
});

// autentikasi login, register, logout
    Route::get('/register', [AuthController::class, 'registerPage'])->name('register');
    Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');

    Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');

    // Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// otomatis ke login jika belum autentikasi
    Route::middleware([AuthMiddleware::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    });

    // otomatis ke dashboard jika sudah autentikasi
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('income', MonthlyIncomeUIController::class)->only(['index', 'store']);
    Route::resource('transaction', TransactionUIController::class)->only(['index', 'store']);
    Route::get('/report', [ReportUIController::class, 'index'])->name('report.index');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/income', [MonthlyIncomeController::class, 'store'])->name('income.store');
    Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
});
