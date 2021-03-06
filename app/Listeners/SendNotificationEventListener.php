<?php

namespace App\Listeners;

use App\Events\SendNotificationEvent;
use App\Foundation\Lib\WechatSmsNotify;
use App\Foundation\Lib\WechatTemplate;
use App\Models\ExperienceBooking;
use App\Models\Sm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendNotificationEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $request;

    public function __construct( Request $request )
    {

        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  SendNotificationEvent $event
     * @return void
     */
    public function handle( SendNotificationEvent $event )
    {
        //判断是否后台下单
        $source=isset($this->request->source)?$this->request->source:'';
        if($event->booking->status==ExperienceBooking::STATUS_UNPAID && $source=='LrssAdmin'){
          $this->unpayNotify($event->booking);
        }
        //成功支付
        if($event->booking->status==ExperienceBooking::STATUS_PAID){

            $this->payNotify($event->booking);
        }
//        取消支付时候的通知,这个地方需要注意，因为取消的时候可能是系统自动取消，也可能是用户取消的，我们确保用户取消的时候发通知
        if(isset($this->request->status) && $event->booking->status==ExperienceBooking::STATUS_CANCEL){

           $this->cancelNotify($event->booking,new WechatTemplate());
        }
    }

    /**
     * 支付完成时候的通知
     */
    public function payNotify( ExperienceBooking $booking )
    {

        $this->_tpl($booking, Sm::TYPE_EXPERIENCE_PAID_WITH_USER,Sm::TYPE_EXPERIENCE_PAID_WITH_OPERATOR);

    }

    /**
     * @param ExperienceBooking $booking
     * 后台下订单，发送后台人员通知
     */
    public function unpayNotify(ExperienceBooking $booking){
        $mobile   = implode(',', config('lrss.experience'));
        $template = Sm::template(SM::TYPE_EXPERIENCE_UNPAY_WITH_OPERATOR, $booking);
       Sm::send($mobile, $template,SM::TYPE_EXPERIENCE_UNPAY_WITH_OPERATOR);

        // 用户
        $template = Sm::template(Sm::TYPE_EXPERIENCE_UNPAY_WITH_USER, $booking);
        Sm::send($booking->mobile, $template, sm::TYPE_EXPERIENCE_UNPAY_WITH_USER);
    }


    /**
     * 取消支付时候的通知
     */
    public function cancelNotify( ExperienceBooking $booking,WechatTemplate $template)
    {
        $this->_tpl($booking, Sm::TYPE_EXPERIENCE_CANCEL_WITH_USER,Sm::TYPE_EXPERIENCE_CANCEL_WITH_OPERATOR);
        //微信service message
        if($this->request->form_id)
        $template->sendCancelTpl($this->request->form_id,$booking->id);
    }


    /**
     * @param ExperienceBooking $booking
     * @param $statusOrder  [用户状态]
     * @param $statusStuff  [运营人员状态]
     */
    private function _tpl( ExperienceBooking $booking, $statusOrder,$statusStuff )
    {

       // 用户
        $template = Sm::template($statusOrder, $booking);
        Sm::send($booking->mobile, $template, $statusOrder);

        // 运营短信通知
        $mobile   = implode(',', config('lrss.experience'));
        $template = Sm::template($statusStuff, $booking);
        Sm::send($mobile, $template, $statusStuff);
        //Log::info('队列在运行中了');

        //wechat 后台人员通知 ----------------- 暂时不要了
        //$this->wechatNotify(config('lrss.experience'), $booking, $statusStuff);
    }

    /**
     * @param array $mobile
     * @param ExperienceBooking $booking
     * @param int $status
     * 微信公众号通知
     */
    private function wechatNotify( array $mobile, ExperienceBooking $booking, int $status )
    {

        $userModel = User::query()->select('open_id')->whereIn('mobile', $mobile)->get();

        //查找出所有的
        foreach ( $userModel as $v ) {
            if ($v->open_id) {
                WechatSmsNotify::sendTpl($booking, $v->open_id, $status);
            }
        }

    }

}
