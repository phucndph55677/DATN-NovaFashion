<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'Chưa thanh toán',
            'Đã thanh toán',
            'Hoàn tiền',
            'Đã hoàn tiền',
        ];

        foreach ($statuses as $status) {
            PaymentStatus::create(['name' => $status]);
        }
    }
}