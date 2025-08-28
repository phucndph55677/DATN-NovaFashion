<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use App\Models\PaymentStatus;
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
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 100, 5000);
        $discount = $this->faker->randomFloat(2, 0, $subtotal * 0.3);
        $total_amount = $subtotal - $discount;

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'payment_status_id' => PaymentStatus::inRandomOrder()->first()?->id ?? PaymentStatus::factory(),
            'order_status_id' => OrderStatus::inRandomOrder()->first()?->id ?? OrderStatus::factory(),
            'voucher_id' => Voucher::inRandomOrder()->first()?->id ?? null,
            'order_code' => '#' . strtoupper(Str::random(10)),
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total_amount' => $total_amount,
            'note' => $this->faker->optional()->sentence(),

            'return_rejected' => $this->faker->boolean(20),
            'return_reason' => $this->faker->sentence(6),    // ví dụ: "Sản phẩm bị lỗi"
            'return_bank' => $this->faker->randomElement(['Vietcombank', 'Techcombank', 'MB Bank']),
            'return_stk' => $this->faker->bankAccountNumber(),
            'return_image' => 'https://picsum.photos/600/400?random=' . fake()->numberBetween(1, 1000),
        ];
    }
}