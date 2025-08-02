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
        'payment_status_id',
        'order_status_id',
        'voucher_id',
        'order_code',
        'name',
        'address',
        'phone',
        'subtotal',
        'discount',
        'total_amount',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentStatus()
    {
        return $this->belongsTo(paymentStatus::class);
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

    // (Tuỳ chọn) Hiển thị màu trạng thái đơn hàng
    public function getOrderBadgeColorAttribute()
    {
        $status = $this->orderStatus?->name;

        return match (true) {
            in_array($status, ['Chờ xác nhận', 'Đã xác nhận']) => 'info',
            $status === 'Chuẩn bị hàng' => 'secondary',
            $status === 'Đang giao hàng' => 'warning',
            $status === 'Đã giao hàng' => 'success',
            $status === 'Thành công' => 'primary',
            $status === 'Hoàn hàng' => 'dark',
            $status === 'Hủy đơn' => 'danger',
            default => 'light',
        };
    }

    // (Tuỳ chọn) Hiển thị màu trạng thái thanh toán
    public function getPaymentBadgeColorAttribute()
    {
        $status = $this->paymentStatus?->name;

        return match (true) {
            $status === 'Chưa thanh toán' => 'danger',
            $status === 'Đã thanh toán' => 'success',
            $status === 'Hoàn tiền' => 'dark',
            default => 'light',
        };
    }
}
