<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentTest extends TestCase
{

    public function test_outcoming_payment_overdraft()
    {
        $user = User::factory()->create();
        $currencyCZK = Currency::factory()->create([
            'code' => 'CZK',
            'rate' => null
        ]);
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currency_code' => $currencyCZK->code,
        ]);

        $response = $this->actingAs($user)->post('/incoming-payment/new', [
            'amount' => 1000,
            'currency' => $currencyCZK->code,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('payments', [
            'account_iban' => $account->iban,
            'amount' => 1000,
        ]);

        $response = $this->actingAs($user)->post('/outcoming-payment/new', [
            'amount' => 1090,
            'currency' => $currencyCZK->code,
        ]);


        $this->assertDatabaseHas('accounts', [
            'iban' => $account->iban,
            'balance' => -99,
        ]);

        $response->assertSessionDoesntHaveErrors();

    }

    public function test_incoming_payment_czk()
    {
        $user = User::factory()->create();
        $currencyCZK = Currency::factory()->create([
            'code' => 'CZK',
            'rate' => null
        ]);
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currency_code' => $currencyCZK->code,
        ]);

        $randomNumber = fake()->numberBetween(100, 2000);
        $response = $this->actingAs($user)->post('/incoming-payment/new', [
            'amount' => $randomNumber,
            'currency' => $currencyCZK->code,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('payments', [
            'account_iban' => $account->iban,
            'amount' => $randomNumber,
        ]);
    }

    public function test_incoming_payment_convert()
    {
        $user = User::factory()->create();
        $currency = Currency::factory()->create();
        $currencyCZK = Currency::factory()->create([
            'code' => 'CZK',
            'rate' => null
        ]);
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currency_code' => $currencyCZK->code,
        ]);

        $randomNumber = fake()->numberBetween(100, 2000);
        $convertedAmount = Currency::convertToCZK($currency->code, $randomNumber);
        $response = $this->actingAs($user)->post('/incoming-payment/new', [
            'amount' => $randomNumber,
            'currency' => $currency->code,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('payments', [
            'account_iban' => $account->iban,
            'amount' => $convertedAmount,
        ]);
    }

    public function test_outcoming_payment_czk()
    {
        $user = User::factory()->create();
        $currencyCZK = Currency::factory()->create([
            'code' => 'CZK',
            'rate' => null
        ]);
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currency_code' => $currencyCZK->code,
        ]);

        $response = $this->actingAs($user)->post('/incoming-payment/new', [
            'amount' => 1000,
            'currency' => $currencyCZK->code,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('payments', [
            'account_iban' => $account->iban,
            'amount' => 1000,
        ]);

        $response = $this->actingAs($user)->post('/outcoming-payment/new', [
            'amount' => 500,
            'currency' => $currencyCZK->code,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('payments', [
            'account_iban' => $account->iban,
            'amount' => -500,
        ]);
    }


    public function test_outcoming_payment_convert()
    {
        $user = User::factory()->create();
        $currencyCZK = Currency::factory()->create([
            'code' => 'CZK',
            'rate' => null
        ]);
        $currency = Currency::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currency_code' => $currencyCZK->code,
        ]);

        $response = $this->actingAs($user)->post('/incoming-payment/new', [
            'amount' => 1000,
            'currency' => $currencyCZK->code,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('payments', [
            'account_iban' => $account->iban,
            'amount' => 1000,
        ]);

        $response = $this->actingAs($user)->post('/outcoming-payment/new', [
            'amount' => 1,
            'currency' => $currency->code,
        ]);

        $response->assertSessionDoesntHaveErrors();

    }

    public function test_outcoming_payment_convert_czk()
    {
        $user = User::factory()->create();
        $currencyCZK = Currency::factory()->create([
            'code' => 'CZK',
            'rate' => null
        ]);
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currency_code' => $currencyCZK->code,
        ]);

        $response = $this->actingAs($user)->post('/incoming-payment/new', [
            'amount' => 1000,
            'currency' => $currencyCZK->code,
        ]);

        $response->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('payments', [
            'account_iban' => $account->iban,
            'amount' => 1000,
        ]);

        $response = $this->actingAs($user)->post('/outcoming-payment/new', [
            'amount' => 100000,
            'currency' => $currencyCZK->code,
        ]);

        $response->assertSessionHasErrors();

    }

    public function test_outcoming_payment_no_czk_account()
    {
        $user = User::factory()->create();
        $currency = Currency::factory()->create();
        $account = Account::factory()->create([
            'user_id' => $user->id,
            'currency_code' => $currency->code,
        ]);

        $response = $this->actingAs($user)->post('/incoming-payment/new', [
            'amount' => 1000,
            'currency' => $currency->code,
        ]);

        $response->assertSessionHasErrors();

    }
}
