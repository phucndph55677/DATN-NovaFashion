<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'Chờ xác nhận',
            'Đã xác nhận',
            'Chưa thanh toán',
            'Đã thanh toán',
            'Đang chuẩn bị hàng',
            'Đang giao hàng',
            'Đã giao hàng',
            'Thành công',
            'Hoàn hàng',
            'Hủy đơn',
        ];

        foreach ($statuses as $status) {
            OrderStatus::create(['name' => $status]);
        }
    }
}
