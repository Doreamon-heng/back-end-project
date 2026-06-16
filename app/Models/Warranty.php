<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Warranty extends Model
{
    protected $fillable = [
        'warranty_date',
        'product_id',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
