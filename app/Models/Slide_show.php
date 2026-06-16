<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide_show extends Model
{
    protected $fillable = [
        'title',
        'desc',
        'image',
        'sub_title',
    ];

    public function getImageAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
