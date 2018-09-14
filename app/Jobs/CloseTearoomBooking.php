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

        if ($this->order->status == TearoomBooking::STATUS_UNPAID) {


            $this->order->update([ 'status' => TearoomBooking::STATUS_CANCEL ]);

            app(TearoomScheduleRepository::class)->unlockTime($this->order->tearoom_id, $this->order->date, $this->order->start_point, $this->order->end_point);
        }

    }
}
