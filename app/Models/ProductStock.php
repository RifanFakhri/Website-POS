<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $fillable = [
        'product_code',
        'stock',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }
}
