<?php

namespace App\Console;

use App\Console\Commands\CancelOrder;
use App\Console\Commands\CancelSpecialPrice;
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
        CancelOrder::class,  //取消订单
        CancelSpecialPrice::class  //取消特殊价格
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

         $schedule->command('cancelOrder')->withoutOverlapping()
                  ->everyMinute();

         $schedule->command('cancelSpecialPrice')->withoutOverlapping()->daily();
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
