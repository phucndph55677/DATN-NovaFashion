<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $methods = [
            ['name' => 'Thanh toán khi giao hàng', 'code' => 'cod', 'type' => 'offline'],
            ['name' => 'Ví điện tử Momo',          'code' => 'momo', 'type' => 'online'],
            ['name' => 'Ngân hàng VNPAY',          'code' => 'vnpay', 'type' => 'online'],
        ];

        return $this->faker->randomElement($methods);
    }
}
