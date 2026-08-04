<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{

    use HasFactory;
    protected $table = 'stocks';
    protected $fillable = [
        'max_quantity',
        'min_quantity',
        'product_id',

    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
