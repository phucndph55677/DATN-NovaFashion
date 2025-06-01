<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\ProductVariant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Lấy order và product_variant ngẫu nhiên hoặc tạo mới nếu chưa có
        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();
        $productVariant = ProductVariant::inRandomOrder()->first() ?? ProductVariant::factory()->create();
        $estimatedDelivery = $this->faker->optional()->dateTimeBetween('now', '+30 days');

        return [
            'order_id' => $order->id,
            'product_variant_id' => $productVariant->id,
            'price' => $productVariant->price ?? $this->faker->randomFloat(2, 50, 500),
            'quantity' => $this->faker->numberBetween(1, 5),
            'status' => $this->faker->randomElement([0,1,2,3]),
            'estimated_delivery' => $estimatedDelivery ? $estimatedDelivery->format('Y-m-d') : null,
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
