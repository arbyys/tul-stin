<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => fake()->unique()->randomNumber(3),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => null,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'google2fa_secret' => 'eyJpdiI6IndIUk9GUG9LY1NiQWhOM1VBazQvN0E9PSIsInZhbHVlIjoiRkJza2RtUUN3Z2RuUjNZK05JbHJSNitGQjI0R2QxZ0pnV1B4NU5idmw0ST0iLCJtYWMiOiI1N2JiNzFlMzMwYjQxZDEzZWYzYTU0ZTlhYzc4MDdlN2FhMzlmMTU1OTU1NTdkOWJjMGU1MzM1OTUzYzEyOTA5IiwidGFnIjoiIn0',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
