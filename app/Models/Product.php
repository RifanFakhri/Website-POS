<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_code',
        'name',
        'category_id',
        'price',
        'image',
    ];

    public function stock()
    {
        return $this->hasOne(ProductStock::class, 'product_code', 'product_code');
    }
}
