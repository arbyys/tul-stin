<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();

        $currency = Currency::inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'currency_code' => $currency->code,
            'balance' => fake()->numberBetween(1000, 100000),
        ];
    }
}
