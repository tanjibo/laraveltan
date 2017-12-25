<?php

namespace App\Http\ApiControllers\Experience;

use App\Foundation\Lib\Payment;
use App\Http\ApiControllers\ApiController;
use App\Http\Resources\Experience\ExperienceRoomBookingResource;
use App\Jobs\SendBookingEmail;
use App\Jobs\SendRefundFailEmail;
use App\Models\ExperienceBooking;
use App\Models\ExperienceSpecialRoomBooking;
use App\Models\ExperienceSpecialRoomBookingXinyuege;
use App\Models\PaymentLog;
use App\Notifications\RefundFailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Naux\Mail\SendCloudTemplate;
use Repositories\ExperienceRoomBookingRepository;
use Repositories\MiniDateRepository;
use Repositories\PaymentRepository;

class ExperienceRoomBookingController extends ApiController
{

    public $bookingRepository, $dateRepository, $payment;

    public function __construct( ExperienceRoomBookingRepository $bookingRepository, MiniDateRepository $date, PaymentRepository $payment )
    {
        $this->bookingRepository = $bookingRepository;
        $this->dateRepository    = $date;
        $this->payment           = $payment;
    }

    /**
     * @param string $room_id
     * 获取一个房间不可入住的时间
     */
    public function RoomCheckinDisableBy(Request $request,$room_id)
    {
        $request['room_id']=$room_id;
        $this->validate($request,[
                'room_id'=>'required|numeric|max:10|min:1', //房间id 不能为空
                'checkin'=>'date_format:Y-m-d'
        ]);

        return $this->success($this->bookingRepository->RoomCheckinDisableApi());
    }


    /**
     * 获取一个房间不可退房时间
     * @return mixed
     */
    public function RoomCheckoutDisableBy(Request $request,$room_id)
    {
        $request['room_id']=$room_id;

        $this->validate($request,[
            'room_id'=>'required|numeric|max:10|min:1', //房间id 不能为空
            'checkin'=>'required|date_format:Y-m-d'
        ]);

        return $this->success($this->bookingRepository->RoomCheckoutDisableApi());
    }

    /**
     * 剩余可以预订的房间
     * @return mixed
     */
    public function leftCheckinRoom(Request $request,$room_id)
    {
        $request['room_id']=$room_id;

        $this->validate($request,[
            'room_id'=>'required|numeric|max:10|min:1', //房间id 不能为空
            'checkin'=>'required|date_format:Y-m-d',
            'checkout'=>'required|date_format:Y-m-d'
        ]);

        if ($resource = $this->bookingRepository->leftCheckinRoomApi()) {
            return $this->success($resource);
        }
        else {
            return $this->notFound();
        }
    }

    /**
     * 订单价格
     * @param Request $request
     * @return mixed
     */
    public function orderTotalFee( Request $request )
    {
        if (is_string($request->rooms)) {
            $request->rooms = json_decode($request->rooms, true);
        }
        $this->validate($request,[
            'checkin'=>'required|date_format:Y-m-d',
            'checkout'=>'required|date_format:Y-m-d',
            'rooms'=>'required|array', //房间id 不能为空

        ]);
        return $this->success([ 'total' => ExperienceBooking::calculateFee($request->checkin, $request->checkout, $request->rooms) ]);
    }


    /**
     * 创建订单
     * @param Request $request
     * @return mixed
     */
    public function createBookingOrder( Request $request )
    {

        if (is_string($request->rooms)) {
            $request['rooms'] = json_decode($request->rooms, true);
        }

        $this->validate($request,[
            'checkin'=>'required|date_format:Y-m-d', //入住时间
            'checkout'=>'required|date_format:Y-m-d', //退房时间
            'customer'=>'required|max:10',           //顾客
            'gender'=>'required',
            'pay_mode'=>'required', //支付方式
            'people'=>'required|max:10|min:1',//人数
            'rooms'=>'required|array', //房间id 不能为空
            'mobile'=>'bail|required|size:11|regex:/^1[34578][0-9]{9}$/', //电话号码

        ]);

        //if ($model = ExperienceBooking::query()->first()) {

        if ($model = ExperienceBooking::store($request)) {
            //和微信支付交互

            $data = $this->payment->unifiedorder($model);
            //booking_id
            $data['booking_id']=$model->id;

            return $this->success($data);
        }
        else {
            return $this->internalError();
        }


    }


    /**
     * @param Request $request
     * @return mixed
     * 重新支付
     */
    public function repay( Request $request )
    {


        if ($model = ExperienceBooking::query()->find($request->booking_id)) {
            //和微信支付交互
            if($model->status!=0){
                return $this->error('error');
            }
            $data = $this->payment->unifiedorder($model);

            //booking_id
            $data['booking_id']=$model->id;
            return $this->success($data);
        }
        else {
            return $this->error('没有这个订单');
        }
    }


    /**
     * 订单列表
     */
    public function orderList()
    {

        return $this->success($this->bookingRepository->orderListApi());
    }


    /**
     * 暂时废弃--------------------------------
     * @param Request $request
     * @return mixed
     * 订单详情
     */
    public function orderDetail( Request $request )
    {

        switch ( $request->type ?: 1 ) {
            case 1:
                $data = ExperienceBooking::query()->find($request->booking_id);

                return $this->success(new ExperienceRoomBookingResource($data));


        }
        return [];
    }


    /**
     * @param Request $request
     * @return mixed
     *
     */
    public function orderStatusToChange( Request $request )
    {
        //授权
        $this->authorize('update',ExperienceBooking::query()->find($request->booking_id));


        if (ExperienceBooking::changeBookingOrder($request->booking_id, $request->status)) {

            return $this->message('success');
        }
        else {
            return  $this->failed('fail');
        }
    }

    public function orderToReply()
    {

    }




    public function calendarInit(Request $request)
    {
        $this->validate($request,[
            'room_id'=>'required'
        ]);
        return $this->success($this->dateRepository->getDate());
    }


    /**
     * @param Request $request
     * 微信支付回调
     */
    public function miniNotifyCallback( Request $request )
    {

        extract($this->payment->notify());

        ExperienceBooking::changeBookingOrder($request->booking_id, ExperienceBooking::STATUS_PAID);

        if (!PaymentLog::query()->where('order_number', $out_trade_no)->count()) {
            PaymentLog::query()->create(
                [
                    'order_number' => $out_trade_no,
                    'trade_number' => $transaction_id,
                    'fee'          => $total_fee / 100,
                    'type'         => 3, //小程序
                    'created_at'   => date('Y-m-d H:i:s'),
                ]
            )
            ;

        }
    }


}
