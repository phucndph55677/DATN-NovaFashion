<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    /** @use HasFactory<\Database\Factories\VoucherFactory> */
    use HasFactory;

    protected $fillable = [
        'voucher_code',
        'quantity',
        'total_used',
        'user_limit',
        'sale_price',
        'min_order_value',
        'status',
        'description',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function orderVouchers()
    {
        return $this->hasMany(OrderVoucher::class);
    }
}
