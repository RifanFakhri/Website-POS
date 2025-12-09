<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('pages.dashboard');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard');
});

// GROUP ROUTE dengan PREFIX 'pages'
Route::prefix('pages')->group(function () {

    Route::resource('products', ProductController::class)->except(['create', 'show', 'edit']);

    Route::get('stocks', [ProductStockController::class, 'index'])->name('stocks.index');
    Route::post('stocks', [ProductStockController::class, 'store'])->name('stocks.store');
    Route::resource('transactions', TransactionController::class);
    
});