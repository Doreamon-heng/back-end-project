<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;

class Products extends Model
{
    protected $fillable = [
        'name',
        'price',
        'discount',
        'details',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
    public function customers()
    {
        return $this->hasMany(Customers::class, 'product_id');
    }

    public function orders()
    {
        return $this->hasMany(Orders::class, 'product_id');
    }

    public function payments()
    {
        return $this->hasMany(Payments::class, 'product_id');
    }

    public function banks()
    {
        return $this->hasMany(Bank::class, 'product_id');
    }

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
