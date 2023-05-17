<?php

namespace Tests\Feature;

use App\Console\Commands\FetchRates;
use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CommandTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fetch_rates_command()
    {
        $fakeResponse = "17.05.2023 #94\nzemě|měna|množství|kód|kurz\nAustrálie|dolar|1|AUD|14,529\nBrazílie|real|1|BRL|4,403";

        Http::fake([
            Config::get('API_URL') . '/*' => Http::response($fakeResponse, 200),
        ]);

        $this->artisan('fetch:rates');

        $this->assertEquals(3, Currency::count());
    }
}
