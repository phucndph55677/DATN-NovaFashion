<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Voucher;
use App\Models\Role;
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
            'role_id' => Role::inRandomOrder()->first()?->id ?? Role::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'name' => $this->faker->words(2, true),
            'voucher_code' => strtoupper($this->faker->unique()->bothify('VOUCHER##??')),
            'quantity' => $this->faker->numberBetween(1, 100),
            'sale_price' => $this->faker->randomFloat(2, 5, 50),
            'min_price' => $this->faker->randomFloat(2, 50, 300),
            'max_price' => $this->faker->optional()->randomFloat(2, 100, 500),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
    }
}
