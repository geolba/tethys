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
        'App\Console\Commands\Inspire',
        'App\Console\Commands\DatasetState',
        'App\Console\Commands\SolrIndexBuilder',
        'App\Console\Commands\Log\ClearLogFile'
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
        //     ->hourly()
        //     ->withoutOverlapping()
        //     ->appendOutputTo(storage_path('logs/inspire.log'));

        $schedule->command('log:clear')
        ->daily()
        ->withoutOverlapping();

        $schedule->command('state:dataset')
            ->daily()
            ->withoutOverlapping();

        $schedule->command('cache:clear-expired')
            ->twiceDaily(1, 16);
        //->appendOutputTo(storage_path('logs/cacheClear.log'));
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
