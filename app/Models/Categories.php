<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function Categories()
    {
        return $this->hasMany(Categories::class, 'category_id');
    }
}


