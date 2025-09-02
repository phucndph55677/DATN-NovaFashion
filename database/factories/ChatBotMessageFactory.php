<?php

namespace Database\Factories;

use App\Models\ChatBotMessage;
use App\Models\ChatBotSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatBotMessage>
 */
class ChatBotMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'chat_bot_session_id' => ChatBotSession::inRandomOrder()->first()->id ?? ChatBotSession::factory(),
            'sender_type' => $this->faker->randomElement(['user', 'bot']),
            'message' => $this->faker->sentence(),
            'tokens_used' => $this->faker->optional()->numberBetween(1, 200),
        ];
    }
}
