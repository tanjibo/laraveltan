<?php

namespace App\Listeners;

use App\Events\RefundFailNotificationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Naux\Mail\SendCloudTemplate;

class RefundFailNotificationEventListener
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
     * @param  RefundFailNotificationEvent $event
     * @return void
     */
    public function handle( RefundFailNotificationEvent $event )
    {
        // 模板变量
        $bind_data = [
            'order_id'   => $event->booking->id,
            'real_price' => $event->booking->real_price,
            'customer'   => $event->booking->customer,
            'mobile'     => $event->booking->mobile,
            'checkin'    => $event->booking->checkin,
            'checkout'   => $event->booking->checkout,
        ];
        $template  = new SendCloudTemplate('refund_fail_tpl', $bind_data);

        Mail::raw(
            $template, function( $message ) {
            $message->from(config('lrss.notification.from'), config('lrss.notification.address'));
            $message->to(config('lrss.notification.email'));
        }
        );
    }
}
