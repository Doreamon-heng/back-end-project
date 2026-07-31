<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
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


