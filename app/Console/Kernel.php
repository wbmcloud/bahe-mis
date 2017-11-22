<?php

namespace App\Console;

use App\Console\Commands\GenInviteCodes;
use App\Console\Commands\ImportRolePermissions;
use App\Console\Commands\ManagerActionLog;
use App\Console\Commands\StatAgent;
use App\Console\Commands\StatDateFirstCashOrder;
use App\Console\Commands\StatDateGeneralCashOrder;
use App\Console\Commands\StatDayFlow;
use App\Console\Commands\StatDayRounds;
use App\Console\Commands\SyncGamePlayerInfo;
use App\Console\Commands\SyncLastLoginTime;
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
        ImportRolePermissions::class,
        GenInviteCodes::class,
        StatDateFirstCashOrder::class,
        StatDateGeneralCashOrder::class,
        ManagerActionLog::class,
        SyncGamePlayerInfo::class,
        StatDayFlow::class,
        StatAgent::class,
        StatDayRounds::class,
        SyncLastLoginTime::class,
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
