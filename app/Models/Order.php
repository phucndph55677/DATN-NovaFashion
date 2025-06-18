<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_status_id',
        'order_code',
        'name',
        'address',
        'phone',
        'email',
        'status',
        'total',
        'sale_price',
        'voucher_code',
        'payment',
        'total_amount',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

}
