<?php

namespace Database\Factories;

use App\Models\OrderVoucher;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderVoucher>
 */
class OrderVoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = OrderVoucher::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'voucher_id' => Voucher::inRandomOrder()->first()?->id ?? Voucher::factory(),
            'order_id' => Order::inRandomOrder()->first()?->id ?? Order::factory(),
        ];
    }
}