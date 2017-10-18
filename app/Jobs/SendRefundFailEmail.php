<?php

namespace App\Jobs;

use App\Events\RefundFailNotificationEvent;
use App\Models\ExperienceBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendRefundFailEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected  $booking;
    public function __construct(ExperienceBooking $booking)
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
        event(new RefundFailNotificationEvent($this->booking));
    }
}
