<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiService;

class FetchCurrencies extends Command
{
    protected $signature = 'currencies:fetch';
    protected $description = 'Fetch and store currency rates from API';

    public function handle()
    {
        try {
            ApiService::fetchAndStoreCurrencies();
            $this->info('Currency rates have been successfully updated.');
        } catch (\Exception $e) {
            $this->error('Failed to update currency rates: ' . $e->getMessage());
        }
    }
} 