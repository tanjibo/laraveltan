<?php

namespace App\Console\Commands;

use App\Models\ExperienceBooking;
use Carbon\Carbon;
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
//       CancelOrderController::canBookingNormal();
        static::cancelBookingNorMal();
    }

    /**
     * 自动取消没有支付的订单
     */
    private function cancelBookingNorMal()
    {
        $date = new Carbon('now');
        if (\App::environment() == 'develop' ||\App::environment() == 'test') {
            $before = $date->subMinutes(1);
        }
        else {
            $before = $date->subMinutes(10);
        }

        $data = ExperienceBooking::query()->where([ [ 'created_at', '<=', $before ], [ 'status', 0 ], [ 'pay_mode', '!=', ExperienceBooking::PAY_MODE_OFFLINE ] ])->orderBy('created_at', 'desc')->get();

        if (count($data->toArray())) {
            foreach ( $data as $v ) {
                ExperienceBooking::changeBookingOrder($v->id, -10, true);
            }
        }
    }
}
