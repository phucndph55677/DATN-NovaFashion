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
        $subtotal = fake()->randomFloat(2, 100, 1000);
        $discount = fake()->randomFloat(2, 0, $subtotal * 0.3);
        $total = $subtotal - $discount;

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'voucher_id' => Voucher::inRandomOrder()->first()?->id ?? null,
            'quantity' => fake()->numberBetween(1, 5),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total_amount' => $total,
        ];
    }
}