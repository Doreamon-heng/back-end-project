<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;

class Categories_image extends Model
{
    use HasFactory;
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

