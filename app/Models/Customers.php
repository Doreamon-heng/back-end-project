<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bank;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Payments;
use App\Models\Orders;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Customers extends Model
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
        'bank_id',
        'account_name',
        'product_id',
        'category_id',
        'payment_id',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payments::class, 'payment_id');
    }

    public function orders()
    {
        return $this->hasMany(Orders::class, 'customer_id');
    }

}
