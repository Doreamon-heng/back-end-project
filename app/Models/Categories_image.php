<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories_image extends Model
{
    protected $table = 'categories_images';
    protected $fillable = [
        'category_id',
        'file_path',
        'image_url',
        'file_name',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}

