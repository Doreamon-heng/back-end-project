<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Payments extends Model
{
    protected $fillable = [
        'receiver_phone',
        'receiver_location',
        'transfer_image',
        'order_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
