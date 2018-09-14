<?php

namespace App\Listeners;

use App\Events\TearoomRefundFailNotificationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Naux\Mail\SendCloudTemplate;

class TearoomRefundFailNotificationEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TearoomRefundFailNotificationEvent  $event
     * @return void
     */
    public function handle(TearoomRefundFailNotificationEvent $event)
    {
        // 模板变量
        $bind_data = [
            'order_id'   => $event->booking->id,
            'real_fee' => $event->booking->real_fee,
            'customer'   => $event->booking->customer,
            'mobile'     => $event->booking->mobile,
            'date'    => $event->booking->date,
            'time'   => $event->booking->time,
        ];
        $template  = new SendCloudTemplate('tearoom_booking_refund_fail_tpl', $bind_data);

        Mail::raw(
            $template, function( $message ) {
            $message->from(config('lrss.notification.from'), config('lrss.notification.address'));
            $message->to(config('lrss.notification.email'));
        }
        );
    }
}
