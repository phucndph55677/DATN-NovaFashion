<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'voucher_id',
        'order_id',
    ];

    // Relationship với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship với Voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    // Relationship với Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
