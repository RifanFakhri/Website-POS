<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code', 
        'customer_name', 
        'total_price', 
        'payment_method'
    ];

    // Relasi ke item-item transaksi
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}