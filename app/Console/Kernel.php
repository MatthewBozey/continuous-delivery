<?php

namespace App\Console;

use App\Helpers\DictionaryCollectorHelper as DCH;
use App\Jobs\DictionaryCollector;
use App\Jobs\MetadataCollector;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('model:prune')->daily();
        $schedule->job(new DictionaryCollector(DCH::KRON_PACKAGE_TYPE, DCH::KRON_PROJECT_ID, DCH::KRON_DATABASE_NAME, DCH::KRON_REF_DATABASE))->everyFiveMinutes()->when(function () {
            return app()->environment('production') || app()->environment('testing');
        });
        $schedule->job(new DictionaryCollector(DCH::KRON_TM_PACKAGE_TYPE, DCH::KRON_TM_PROJECT_ID, DCH::KRON_TM_DATABASE_NAME, DCH::KRON_TM_REF_DATABASE))->everyFiveMinutes()->when(function () {
            return app()->environment('production') || app()->environment('testing');
        });
        $schedule->job(new MetadataCollector())->everyFiveMinutes()->when(function () {
            return app()->environment('production') || app()->environment('testing');
        });

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
