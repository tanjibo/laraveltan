<?php

namespace App\Http\ApiControllers\Front;

use App\Http\ApiControllers\ApiController;
use App\Http\Resources\Front\ExperienceRoomBookingResource;
use App\Http\Resources\Front\ExperienceRoomBookingSanResource;
use App\Http\Resources\Front\ExperienceRoomBookingXingResource;
use App\Models\ExperienceBooking;
use App\Models\ExperienceSpecialRoomBooking;
use App\Models\ExperienceSpecialRoomBookingXinyuege;
use App\Models\PaymentLog;
use Illuminate\Http\Request;
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
    public function RoomCheckinDisableBy()
    {
        return $this->success($this->bookingRepository->RoomCheckinDisableApi());
    }


    /**
     * 获取一个房间不可退房时间
     * @return mixed
     */
    public function RoomCheckoutDisableBy()
    {
        return $this->success($this->bookingRepository->RoomCheckoutDisableApi());
    }

    /**
     * 剩余可以预订的房间
     * @return mixed
     */
    public function leftCheckinRoom()
    {
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
            $request->rooms = json_decode($request->rooms, true);
        }

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
            case 3:
                $data = ExperienceSpecialRoomBooking::query()->find($request->booking_id);
                return $this->success(new ExperienceRoomBookingSanResource($data));

            case 4:
                $data = ExperienceSpecialRoomBookingXinyuege::query()->find($request->booking_id);
                return $this->success(new ExperienceRoomBookingXingResource($data));
        }
        return [];
    }


    public function orderStatusToChange( Request $request )
    {
        if (ExperienceBooking::changeBookingOrder($request->booking_id, $request->status)) {
            $this->message('success');
        }
        else {
            $this->failed('fail');
        }
    }

    public function orderToReply()
    {

    }


    public function calendarInit()
    {

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
                    'type'         => PaymentLog::TYPE_MINI,
                    'created_at'   => date('Y-m-d H:i:s'),
                ]
            )
            ;

        }
    }


}
