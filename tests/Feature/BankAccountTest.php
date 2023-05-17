<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PragmaRX\Google2FAQRCode\Google2FA;
use Tests\Helpers\FakeGoogle2FA;
use Tests\TestCase;

class BankAccountTest extends TestCase
{

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

        $response2 = $this->actingAs($user)->post('/accounts/create', [
            'currency' => $currency->code,
        ]);

        $response2->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('accounts', [
            'user_id' => $user->id,
            'currency_code' => $currency->code,
        ]);
    }

    public function test_bank_account_creation_fail()
    {
        $user = User::factory()->create();

        $currency = Currency::factory()->create();
        $currencyCZK = Currency::factory()->create([
            'code' => 'CZK',
            'rate' => null
        ]);

        $response = $this->actingAs($user)->post('/accounts/create', [
            'currency' => $currency->code,
        ]);

        $response->assertSessionHasErrors();

        $response2 = $this->actingAs($user)->post('/accounts/create', [
            'currency' => $currencyCZK->code,
        ]);

        $response3 = $this->actingAs($user)->post('/accounts/create', [
            'currency' => $currencyCZK->code,
        ]);

        $response3->assertSessionHasErrors();
    }

    public function test_bank_account_removal()
    {
        $user = User::factory()->create();

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

        $iban = Account::where("user_id", $user->id)->where("currency_code", $currencyCZK->code)->first()->iban;

        $response2 = $this->actingAs($user)->post('/accounts/remove', [
            'iban' => $iban,
        ]);

        $response2->assertSessionDoesntHaveErrors();

        $this->assertDatabaseMissing('accounts', [
            'user_id' => $user->id,
            'currency_code' => $currencyCZK->code,
        ]);
    }
}
