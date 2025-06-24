<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Voucher;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Voucher::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 week', 'now');
        $endDate = $this->faker->dateTimeBetween('now', '+1 month');

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'voucher_code' => strtoupper($this->faker->unique()->bothify('VOUCHER##??')),
            'quantity' => $this->faker->numberBetween(1, 100),
            'total_used' => 0,
            'user_limit' => $this->faker->numberBetween(1, 3),
            'sale_price' => $this->faker->randomFloat(2, 5, 50),
            'max_discount' => $this->faker->numberBetween(10000, 500000),
            'min_price' => $this->faker->randomFloat(2, 50, 300),
            'status' => 1,
            'description' => $this->faker->sentence(),
            'start_date' => $startDate->format('Y-m-d H:i:s'),
            'end_date' => $endDate->format('Y-m-d H:i:s'),
        ];
    }
}
