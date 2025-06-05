<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Banner>
 */
class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $endDate = $this->faker->dateTimeBetween('now', '+2 months');

        return [
            'image' => $this->faker->imageUrl(1200, 400, 'products', true, 'Banner'),
            'status' => $this->faker->boolean(80), // 80% hiển thị
            'product_link' => $this->faker->optional()->url(),
            'description' => $this->faker->optional()->sentence(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
    }
}
