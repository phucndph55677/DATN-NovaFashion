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
        'voucher_id',
        'order_code',
        'name',
        'address',
        'phone',
        'email',
        'subtotal',
        'discount',
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

     public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getBadgeColorAttribute()
    {
        $status = $this->orderStatus?->name;

        return match (true) {
            in_array($status, ['Chờ xác nhận', 'Đã xác nhận']) => 'info',
            in_array($status, ['Chưa thanh toán', 'Đã thanh toán']) => 'success',
            $status === 'Chuẩn bị hàng' => 'secondary',
            in_array($status, ['Đang giao hàng', 'Đã giao hàng']) => 'warning',
            $status === 'Thành công' => 'primary',
            $status === 'Hoàn hàng' => 'dark',
            $status === 'Hủy đơn' => 'danger',
            default => 'light',
        };
    }

}
