<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    protected $fillable = [
        'user_id',
        'otp_code',
        'is_used',
        'issued_at',
        'expires_at',
        'verify',
        'role_id',
    ];
}
