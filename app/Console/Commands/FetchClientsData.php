<?php

namespace App\Console\Commands;

use App\Services\ApiService;
use Illuminate\Console\Command;

class FetchClientsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch clients data from API and store in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting clients data fetch...');
        ApiService::fetchAndStoreClients();
        $this->info('Clients data fetch completed.');
    }
}
