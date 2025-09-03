<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            ['name' => 'Thanh toán khi giao hàng', 'code' => 'cod', 'type' => 'offline'],
            ['name' => 'Ví điện tử Momo',          'code' => 'momo', 'type' => 'online'],
            // ['name' => 'Ngân hàng VNPAY',          'code' => 'vnpay', 'type' => 'online'],
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method);
        }
    }
}
