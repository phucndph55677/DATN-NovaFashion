<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderStatus;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = $this->faker->randomFloat(2, 100, 5000);
        $sale_price = $this->faker->randomFloat(2, 0, $total * 0.3);
        $total_amount = $total - $sale_price;

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'order_status_id' => OrderStatus::inRandomOrder()->first()?->id ?? OrderStatus::factory(),
            'order_code' => '#' . strtoupper(Str::random(10)),
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'total' => $total,
            'sale_price' => $sale_price,
            'voucher_code' => $this->faker->optional()->bothify('VOUCHER-#####'),
            'payment' => $this->faker->randomElement(['Tiền mặt', 'Thẻ tín dụng', 'Chuyển khoản']),
            'total_amount' => $total_amount,
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
