<?php

namespace App\Jobs;

use App\Events\TearoomRefundFailNotificationEvent;
use App\Models\Api\TearoomBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendTearoomRefundFailEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected  $booking;
    public function __construct(TearoomBooking $booking)
    {
        $this->booking=$booking;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new TearoomRefundFailNotificationEvent($this->booking));
    }
}
