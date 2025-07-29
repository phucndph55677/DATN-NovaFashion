<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Payment;
use App\Models\Order;
use App\Models\PaymentMethod;

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
        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();
        $method = PaymentMethod::inRandomOrder()->first() ?? PaymentMethod::factory()->create();

        return [
            'order_id' => $order->id,
            'payment_method_id' => $method->id,
            'payment_amount' => $this->faker->randomFloat(2, 100, 5000),
            'payment_code' => strtoupper('PAY-' . $this->faker->unique()->bothify('####-???')),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
        ];
    }
}
