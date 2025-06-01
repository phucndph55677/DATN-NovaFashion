<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Payment;
use App\Models\User;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /** 
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Payment::class;

    public function definition(): array
    {
        // Lấy user và order ngẫu nhiên hoặc tạo mới nếu chưa có
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();

        $methods = ['bank_transfer', 'credit_card', 'momo', 'zalopay'];
        $statuses = ['pending', 'completed', 'failed'];

        return [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'payment_amount' => $this->faker->randomFloat(2, 100, 5000),
            'payment_method' => $this->faker->randomElement($methods),
            'payment_status' => $this->faker->randomElement($statuses),
            'transaction_code' => strtoupper('PAY-' . $this->faker->unique()->bothify('####-???')), // Ví dụ: PAY-5832-ABC
        ];
    }
}
