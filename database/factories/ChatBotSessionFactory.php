<?php

namespace Database\Factories;

use App\Models\ChatBotSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatBotSession>
 */
class ChatBotSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? null,
            'session_code' => Str::uuid(),
            'title' => $this->faker->sentence(3),
        ];
    }
}
