<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Notification;
use App\Models\User;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    protected $model = Notification::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();

        return [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'title' => $this->faker->sentence(6),
            'content' => $this->faker->paragraph(2),
        ];
    }
}
