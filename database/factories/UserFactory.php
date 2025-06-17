<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\Ranking;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role_id' => Role::inRandomOrder()->first()?->id ?? Role::factory(),
            'ranking_id' => Ranking::inRandomOrder()->first()?->id, // Có thể null nếu chưa có dữ liệu
            'image' => fake()->imageUrl(200, 200, 'people'),
            'name' => fake()->name(),
            'phone' => fake()->unique()->numerify('+84 9## ### ###'),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'), // mặc định cho seeding
            'address' => fake()->address(),
            'is_verified' => fake()->boolean(70), // 70% user đã xác thực
            'status' => $this->faker->numberBetween(0, 1), // random 0 hoặc 1
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
