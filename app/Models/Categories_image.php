<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories_image extends Model
{
    protected $fillable = [
        'category_id',
        'image_url',
        'file_name',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}

