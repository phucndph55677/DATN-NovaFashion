<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Invoice::class;

    public function definition(): array
    {
        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();

        return [
            'order_id' => $order->id,
            'invoice_code' => 'INV-' . strtoupper(Str::random(8)), // Ví dụ: INV-8CHZ9KXW
            'name' => $this->faker->name,
            'address' => $this->faker->address,
            'phone' => fake()->unique()->numerify('+84 9## ### ###'),
            'email' => $this->faker->unique()->safeEmail,
            'status' => $this->faker->numberBetween(0, 2),
            'total' => $order->total_amount ?? $this->faker->randomFloat(2, 100, 5000),
            'issue_date' => $this->faker->date(),
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
