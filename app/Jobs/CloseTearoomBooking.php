<?php

namespace App\Jobs;

use App\Models\Api\TearoomBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Repositories\TearoomScheduleRepository;

class CloseTearoomBooking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $order;

    public function __construct( TearoomBooking $order, $delay )
    {
        $this->order = $order;
        $this->delay($delay);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 判断对应的订单是否已经被支付
        // 如果已经支付则不需要关闭订单，直接退出
        if ($this->order->status == TearoomBooking::STATUS_PAID) {
            return;
        }
        $this->order->update([ 'status' => TearoomBooking::STATUS_CANCEL ]);

        app(TearoomScheduleRepository::class)->unlockTime($this->order->tearoom_id, $this->order->date, $this->order->start_point, $this->order->end_point);
        // 循环遍历订单中的商品 SKU，将订单中的数量加回到 SKU 的库存中去

    }
}
