<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Services\CurrencyService;

class FetchRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches currency rates from ČNB API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info("Starting currency fetch.");
            CurrencyService::updateExchangeRates();
            $this->info("Currency fetch done!");
        }
        catch(Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
