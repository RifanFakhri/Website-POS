<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.dashboard');
});

// Atau, jika Anda ingin mengaksesnya melalui /dashboard
Route::get('/dashboard', function () {
    return view('pages.dashboard');
});