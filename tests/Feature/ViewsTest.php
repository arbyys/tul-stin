<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_accounts_view()
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

        $response = $this->actingAs($user)->get('/accounts');

        $response->assertStatus(200);
        $response->assertViewIs('pages.accounts');
        $response->assertViewHas('currencies', function ($currencies) use ($currencyCZK) {
            return $currencies->contains($currencyCZK);
        });
        $response->assertViewHas('accounts', function ($accounts) use ($account) {
            return $accounts->contains($account);
        });
    }

    public function test_home_view()
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

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('pages.home');

        $response->assertViewHas('accounts', function ($accounts) use ($account) {
            return $accounts->contains($account);
        });
    }

    public function test_history_view()
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

        $response = $this->actingAs($user)->get('/history');

        $response->assertStatus(200);
        $response->assertViewIs('pages.history');

        $response->assertViewHas('accountPayments', function ($accounts) {
            return $accounts !== null;
        });
    }

    public function test_outcoming_payment_view()
    {
        $user = User::factory()->create();
        $currencyCZK = Currency::factory()->create([
            'code' => 'CZK',
            'rate' => null
        ]);

        $response = $this->actingAs($user)->get('/outcoming-payment');

        $response->assertStatus(200);
        $response->assertViewIs('pages.outcoming-payment');

        $response->assertViewHas('currencies', function ($currencies) use ($currencyCZK) {
            return $currencies->contains($currencyCZK);
        });
        $response->assertViewHas('dateUpdated', function ($date) {
            return $date !== null;
        });
    }

    public function test_incoming_payment_view()
    {
        $user = User::factory()->create();
        $currencyCZK = Currency::factory()->create([
            'code' => 'CZK',
            'rate' => null
        ]);

        $response = $this->actingAs($user)->get('/incoming-payment');

        $response->assertStatus(200);
        $response->assertViewIs('pages.incoming-payment');

        $response->assertViewHas('currencies', function ($currencies) use ($currencyCZK) {
            return $currencies->contains($currencyCZK);
        });
        $response->assertViewHas('dateUpdated', function ($date) {
            return $date !== null;
        });
    }
}
