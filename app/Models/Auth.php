<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Auth extends Model
{
    use HasFactory, Notifiable, HasApiTokens;
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
