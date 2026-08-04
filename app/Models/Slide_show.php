<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SlideShowImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slide_show extends Model
{
    use HasFactory;

    protected $table = 'slide_shows';
    protected $fillable = [
        'title',
        'desc',
        'sub_title',
    ];

    public function getImageAttribute($value)
    {
        return asset('storage/' . $value);
    }

    public function slideShowImages()
    {
        return $this->hasMany(SlideShowImages::class, 'slide_show_id');
    }
}
