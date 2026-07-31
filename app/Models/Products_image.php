<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Products_image extends Model
{
      protected $table = 'product_images';

    protected $fillable = [
        'product_id',
        'file_name',
        'file_path',
        'image_url',
        'is_enabled'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    public function getStatusLabelAttribute()
    {
        return $this->is_enabled ? 'enabled' : 'disabled';
    }
}
