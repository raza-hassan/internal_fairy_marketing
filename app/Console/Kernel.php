<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected $commands = [
        Commands\FairyCron::class,
        Commands\DemoCron::class,
        Commands\TargetsCron::class,
        Commands\RefreshFacebookToken::class,

    ];

    protected function schedule(Schedule $schedule)
    {

        // $schedule->command('fairy:cron')->daily();
        // $schedule->command('App\Http\Controllers\FacebookApiController@facebookleads')->daily();
        // $schedule->command('targets:cron')->everyMinute();

        // $schedule->command('stagging:cron')->everyThirtyMinutes();
        // $schedule->command('stagging:cron')
        //     ->everyTenMinutes()
        //     ->appendOutputTo(storage_path('logs/stagging-cron.log'));


        // $schedule->command('stagging:cron')
        //     ->everyFifteenMinutes()
        //     ->appendOutputTo(storage_path('logs/stagging-cron.log'));

        $schedule->command('stagging:cron')
            ->everyThirtyMinutes()
            ->appendOutputTo(storage_path('logs/stagging-cron.log'));

        $schedule->command('facebook:refresh-token')->daily();
        $schedule->command('targets:cron')->daily();
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
