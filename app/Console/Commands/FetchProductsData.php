<?php

namespace App\Console\Commands;

use App\Services\ApiService;
use Illuminate\Console\Command;

class FetchProductsData extends Command
{
    protected $signature = 'fetch:products';

    protected $description = 'Fetch products data from API and store in database';

    public function handle()
    {
        $this->info('Starting to fetch products data...');
        
        try {
            ApiService::fetchAndStoreProducts();
            $this->info('Products data fetched and stored successfully!');
        } catch (\Exception $e) {
            $this->error('Error fetching products data: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 