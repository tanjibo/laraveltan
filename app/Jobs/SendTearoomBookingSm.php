<?php

namespace App\Jobs;

use App\Events\SendTearoomBackendNotificationEvent;
use App\Models\TearoomBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendTearoomBookingSm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected  $booking;
    public function __construct(TearoomBooking $order)
    {
        $this->booking=$order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new SendTearoomBackendNotificationEvent($this->booking));
    }
}
