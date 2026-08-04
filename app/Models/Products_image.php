<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products_image extends Model
{
    use HasFactory;
      protected $table = 'products_images';

    protected $fillable = [
        'product_id',
        'file_name',
        'file_path',
        'image_url',
        'is_enabled'
    ];

    public function products()
    {
        return $this->belongsTo(Products::class);
    }

    public function getStatusLabelAttribute()
    {
        return $this->is_enabled ? 'enabled' : 'disabled';
    }
}
