<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'content' => fake()->optional(0.7)->paragraph(), // 70% có nội dung đánh giá
            'status' => fake()->randomElement([0, 1]), // có thể pending hoặc approved
        ];
    }
}
