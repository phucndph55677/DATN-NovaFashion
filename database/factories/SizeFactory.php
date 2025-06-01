<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Size>
 */
class SizeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Một số size phổ biến (S, M, L, XL, XXL,...)
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'];

        return [
            'name' => fake()->unique()->randomElement($sizes),
        ];
    }
}
