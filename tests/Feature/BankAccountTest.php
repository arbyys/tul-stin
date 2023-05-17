<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PragmaRX\Google2FAQRCode\Google2FA;
use Tests\Helpers\FakeGoogle2FA;
use Tests\TestCase;

class BankAccountTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_bank_account_creation()
    {
        $user = User::factory()->create();

        $currency = Currency::factory()->create();
        $currencyCZK = Currency::factory()->create([
            'code' => 'CZK',
            'rate' => null
        ]);

        $response = $this->actingAs($user)->post('/accounts/create', [
            'currency' => $currencyCZK->code,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('accounts', [
            'user_id' => $user->id,
            'currency_code' => $currencyCZK->code,
        ]);
    }
}
