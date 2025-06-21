<?php

namespace Database\Factories;

use App\Models\Banner;
use App\Models\Location;
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
            'location_id' => Location::inRandomOrder()->first()?->id ?? Location::factory(), // random có sẵn hoặc tự tạo mới
            'name' => $this->faker->sentence(3),
            'image' => $this->faker->imageUrl(1200, 400, 'products', true, 'Banner'),
            'status' => $this->faker->boolean(80), // 80% hiển thị
            'product_link' => $this->faker->optional()->url(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
    }
}
