<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $account = Account::inRandomOrder()->first();

        return [
            'account_iban' => $account->iban,
            'amount' => fake()->randomFloat(2, -1000, 1000),
        ];
    }
}
