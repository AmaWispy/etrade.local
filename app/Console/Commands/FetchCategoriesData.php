<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiService;
use Illuminate\Support\Facades\Log;

class FetchCategoriesData extends Command
{
    protected $signature = 'fetch:categories';
    protected $description = 'Fetch categories data from external API';

    public function handle()
    {
        $this->info('Starting categories fetch...');
        
        try {
            $result = ApiService::fetchAndStoreCategories();
            
            if ($result) {
                $this->info('Categories data fetched successfully!');
                return 0;
            } else {
                $this->error('Failed to fetch categories data');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('Error fetching categories: ' . $e->getMessage());
            Log::error('Categories fetch command failed: ' . $e->getMessage());
            return 1;
        }
    }
} 