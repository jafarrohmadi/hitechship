<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\DropTables::class,
        \App\Console\Commands\getShipData::class,
        \App\Console\Commands\getHistoryShipData::class,
        \App\Console\Commands\fixMoving::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('getShip:data')->everyMinute();
        $schedule->command('getHistoryShip:data')->everyMinute();
        $schedule->command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();
        $schedule->command('fix:moving')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
