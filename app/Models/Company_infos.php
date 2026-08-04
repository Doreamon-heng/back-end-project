<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company_infos extends Model
{
    use HasFactory;
    protected $table = 'company_infos';
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
