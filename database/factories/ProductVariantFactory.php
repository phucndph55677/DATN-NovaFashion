<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $basePrice = fake()->numberBetween(100_000, 2_000_000);
        $discount = fake()->boolean(50) ? fake()->numberBetween(10_000, $basePrice - 10_000) : null;

        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'color_id' => Color::inRandomOrder()->first()?->id ?? Color::factory(),
            'size_id' => Size::inRandomOrder()->first()?->id ?? Size::factory(),
            'image' => fake()->imageUrl(600, 600, 'fashion'),
            'price' => $basePrice,
            'sale' => $discount, // giờ có thể là null hoặc giá giảm
            'quantity' => fake()->numberBetween(0, 100),
            'is_active' => fake()->boolean(90), // 90% còn bán
        ];
    }
}
