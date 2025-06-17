<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Chat;
use App\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatDetail>
 */
class ChatDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chat_id' => Chat::inRandomOrder()->first()?->id ?? Chat::factory(),
            'sender_id'   => User::inRandomOrder()->first()?->id ?? User::factory(),
            'receiver_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'message' => $this->faker->paragraph(),
        ];
    }
}
