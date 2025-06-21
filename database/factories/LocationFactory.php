<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Trang chủ - Đầu trang',
                'Trang chủ - Giữa trang',
                'Danh mục nam - Đầu trang',
                'Danh mục nữ - Giữa trang',
                'Chi tiết sản phẩm'
            ]),
        ];
    }
}
