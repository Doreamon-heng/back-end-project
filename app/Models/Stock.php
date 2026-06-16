<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Stock extends Model
{
    protected $fillable = [
        'max',
        'min',
        'product_id',

    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
}
