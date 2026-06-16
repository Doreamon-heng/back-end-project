<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Otps extends Model
{
    protected $fillable = [
        'otp_code',
        'issued_at',
        'expires_at',
        'verify',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
