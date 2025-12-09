<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // No. Transaksi (misalnya TRX60030094)
            $table->string('customer_name')->default('Umum'); // Nama Pemesan
            $table->decimal('total_price', 15, 2); // Total Harga
            $table->enum('payment_method', ['QRIS', 'Tunai', 'Debit'])->default('Tunai'); // Pembayaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};