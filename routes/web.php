<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductStockController;

Route::get('/', function () {
    return view('pages.dashboard');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard');
});

// GROUP ROUTE dengan PREFIX 'pages'
Route::prefix('pages')->group(function () {
    
    // 1. Resource route untuk Produk (CRUD DETAIL PRODUK)
    // URL Index: /pages/products
    // Nama Index: products.index
    Route::resource('products', ProductController::class)->except(['create', 'show', 'edit']);

    // 2. Route KHUSUS untuk Manajemen Stok (Lihat Stok & Tambah Stok)
    // URL Index: /pages/stocks
    // Nama Index: stocks.index
    Route::get('stocks', [ProductStockController::class, 'index'])->name('stocks.index');
    Route::post('stocks', [ProductStockController::class, 'store'])->name('stocks.store');
    
});