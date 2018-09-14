<?php

namespace App\Listeners;

use App\Events\SendTearoomBackendNotificationEvent;
use App\Models\Sm;
use App\Models\TearoomBooking;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTearoomBackendNotificationEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $request = '';

    public function __construct( Request $request )
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  SendTearoomBackendNotificationEvent $event
     * @return void
     */
    public function handle( SendTearoomBackendNotificationEvent $event )
    {
        if ($event->booking->status == TearoomBooking::STATUS_PAID) {
            $this->createNotify($event->booking);
            return;
        }

        if (isset($this->request->status) && $event->booking->status == TearoomBooking::STATUS_CANCEL) {
            $this->cancelNotify($event->booking);
            return;
        }
    }

    /**
     * 通知
     */
    public function createNotify( TearoomBooking $booking )
    {

        $this->_tpl($booking, Sm::TYPE_TEAROOM_BOOKING_WITH_USER, Sm::TYPE_TEAROOM_BOOKING_WITH_OPERATOR);

    }

    /**
     * 取消支付时候的通知
     */
    public function cancelNotify( TearoomBooking $booking )
    {
        $this->_tpl($booking, Sm::TYPE_TEAROOM_CANCEL_WITH_OPERATOR, Sm::TYPE_TEAROOM_CANCEL_WITH_OPERATOR);

    }


    /**
     * @param TearoomBooking $booking
     * @param $statusOrder [用户状态]
     * @param $statusStuff [运营人员状态]
     */
    private function _tpl( TearoomBooking $booking, $statusOrder, $statusStuff )
    {

        // 用户
        $template = Sm::tearoomTemplate($statusOrder, $booking);
        Sm::send($booking->mobile, $template, $statusOrder);

        // 运营短信通知
        $mobile   = config('lrss.tearoom');
        $template = Sm::tearoomTemplate($statusStuff, $booking);
        Sm::send($mobile, $template, $statusStuff);

    }
}
