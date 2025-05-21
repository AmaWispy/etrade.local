<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\FetchClientsData::class,
        Commands\FetchProductsData::class,
        Commands\FetchCategoriesData::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /* $schedule->command('clients:fetch')
            ->dailyAt('12:00'); */
        $schedule->command('fetch:products')
            ->dailyAt('12:00');
        $schedule->command('currencies:fetch')
            ->dailyAt('12:10');
        $schedule->command('fetch:categories')
            ->dailyAt('12:20');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
