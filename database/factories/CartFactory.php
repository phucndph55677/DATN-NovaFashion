<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cart;
use App\Models\User;
use App\Models\Voucher;

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
    protected $model = Cart::class;

    public function definition(): array
    {
        $price = fake()->randomFloat(2, 100, 1000);
        $quantity = fake()->numberBetween(1, 5);
        $total = $price;

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'quantity' => $quantity,
            'price' => $price,
            'total_amount' => $total,
        ];
    }
}