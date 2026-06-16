<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Orders;

class Delivery extends Model
{
    protected $fillable = [
        'order_id',
        'delivery_date',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
}
