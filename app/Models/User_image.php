<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User_image extends Model
{

    use HasFactory;

    protected $table = 'user_images';
    protected $fillable = [
        'user_id',
        'image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



}
