<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Slide_show;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SlideShowImages extends Model
{
    use HasFactory;
    protected $table = 'slide_shows_image';

    protected $fillable = [
        'slide_show_id',
        'file_name',
        'image_url',
        'is_enabled',
        'is_disabled',
    ];

    public function slide_show()
    {
        return $this->belongsTo(Slide_show::class, 'slide_show_id');
    }

    public function getStatusLabelAttribute()
    {
        return $this->is_enabled ? 'enabled' : 'disabled';
    }
}
