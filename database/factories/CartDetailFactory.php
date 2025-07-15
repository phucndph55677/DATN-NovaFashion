<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cart;
use App\Models\ProductVariant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartDetail>
 */
class CartDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cart = Cart::inRandomOrder()->first() ?? Cart::factory()->create();
        $variant = ProductVariant::inRandomOrder()->first() ?? ProductVariant::factory()->create();

        $price = $variant->price ?? $this->faker->randomFloat(2, 10, 500);
        $quantity = $this->faker->numberBetween(1, 5);

        return [
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => $quantity,
            'price' => $price,
            'total_amount' => $price * $quantity,
        ];
    }
}