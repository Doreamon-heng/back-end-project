<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company_infos extends Model
{
    protected $fillable = [
        'logo',
        'email',
        'phone',
        'name',
        'address',
        'facebook_link',
        'youtube_link',
        'tiktok_link',
        'telegram_link', 
    ];
    public function companyInfo()
    {
        return $this->hasOne(Company_infos::class);
    }
}
