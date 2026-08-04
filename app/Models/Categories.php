<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Categories extends Model

{
    use HasFactory;
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
    ];


    public function Categories()
    {
        return $this->hasMany('categories');
    }

    public function Categories_image()
    {
       return $this->hasOne(Categories_image::class, 'category_id');
    }
}


