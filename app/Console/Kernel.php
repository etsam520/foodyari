<?php

namespace App\Console;

use App\Console\Commands\MakeBreakFastAttendance;
use App\Console\Commands\MakeDinner;
use App\Console\Commands\MakeLunch;
use App\Console\Commands\ProcessScheduledOrders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [
        MakeBreakFastAttendance::class,
        MakeLunch::class,
        MakeDinner::class,
        ProcessScheduledOrders::class
    ];
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // Schedule MakeBreakFastAttendance command at 08:30 AM everyTwentySeconds()
    $schedule->command('attendance:makeBreakfast')->everyTwentySeconds();
    // $schedule->command('attendance:makeBreakfast')->dailyAt('07:58');

    // Schedule MakeLunch command at 12:30 PM
    $schedule->command('attendance:makeLunch')->everyTwentySeconds();
    // $schedule->command('attendance:makeLunch')->dailyAt('11:58');

    // Schedule MakeDinner command at 07:30 PM
    $schedule->command('attendance:makeDinner')->everyTwentySeconds();
    // $schedule->command('attendance:makeDinner')->dailyAt('15:58');
    
    // Process scheduled orders every minute
    $schedule->command('orders:process-scheduled')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
