<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Role;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'role_id' => Role::inRandomOrder()->first()?->id ?? Role::factory(),
            'product_code' => strtoupper(Str::random(8)), // Ví dụ: "A7F3K9BZ"
            'name' => fake()->words(3, true), // Ví dụ: "Wooden Dining Table"
            'image' => 'https://picsum.photos/600/400?random=' . fake()->numberBetween(1, 1000), // Sử dụng picsum.photos thay vì placeholder
            'description' => fake()->optional()->paragraph(),
            'material' => fake()->optional()->word(),
            'onpage' => fake()->boolean(80), // 80% sản phẩm hiển thị
        ];
    }
}
