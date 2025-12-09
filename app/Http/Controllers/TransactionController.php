<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter_payment = $request->input('payment_method');
        
        $query = Transaction::query();

        // Logika Search
        if ($search) {
            $query->where('transaction_code', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%');
        }

        // Logika Filter
        if ($filter_payment && $filter_payment !== 'all') {
            $query->where('payment_method', $filter_payment);
        }

        // Ambil data dengan pagination
        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Opsi metode pembayaran untuk filter
        $paymentMethods = [
            'all' => 'Semua Pembayaran',
            'Tunai' => 'Tunai',
            'QRIS' => 'QRIS',
            'Debit' => 'Debit',
        ];

        return view('pages.transactions', compact('transactions', 'paymentMethods'));
    }

    // Metode store, edit, update, destroy, dll. dapat diimplementasikan sesuai kebutuhan.
    // Karena fokusnya pada tampilan daftar (index), sisanya dapat dikosongkan/ditambahkan nanti.
    
    public function create() {}
    public function store(Request $request) {}
    public function show(Transaction $transaction) {}
    public function edit(Transaction $transaction) {}
    public function update(Request $request, Transaction $transaction) {}
    public function destroy(Transaction $transaction) {}
}