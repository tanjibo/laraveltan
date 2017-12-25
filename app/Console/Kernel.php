<?php

namespace App\Console;

use App\Console\Commands\CancelOrder;
use App\Console\Commands\DelOrder;
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
        DelOrder::class  //删除取消的订单
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //取消没有立即支付的安吉订单
         $schedule->command('cancelOrder')->withoutOverlapping()
                  ->everyMinute();
         //删除取消的订单
         $schedule->command('delOrder:cancel')->withoutOverlapping()->daily();
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
