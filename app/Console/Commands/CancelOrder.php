<?php

namespace App\Console\Commands;

use App\Http\Controllers\CancelOrderController;
use Illuminate\Console\Command;

class CancelOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancelOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '取消没有按时支付的订单';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       CancelOrderController::canBookingNormal();
    }
}
