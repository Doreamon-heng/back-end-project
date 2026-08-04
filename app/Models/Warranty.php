<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Products;

class Warranty extends Model
{
    protected $fillable = [
        'warranty_date',
        'product_id',
    ];
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
