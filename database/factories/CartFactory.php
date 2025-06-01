<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Lấy product ngẫu nhiên hoặc tạo mới nếu chưa có
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        return [
            'product_id' => $product->id,
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'quantity' => fake()->numberBetween(1, 5),
            'price' => $product->price ?? fake()->randomFloat(2, 50, 500), // Giá tại thời điểm thêm vào giỏ
        ];
    }
}
