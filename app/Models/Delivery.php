<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Orders;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{
    use HasFactory;
    protected $table = 'deliveries';
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
