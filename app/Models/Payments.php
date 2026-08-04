<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Orders;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Payments extends Model
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $fillable = [
        'receiver_phone',
        'receiver_location',
        'transfer_image',
        'order_id',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

}
