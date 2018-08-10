<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 7/8/2018
 * Time: 10:24 AM
 */

namespace App\Http\ApiControllers\Tearoom;


use App\Http\ApiControllers\ApiController;
use App\Http\Requests\TearoomBookingRequest;
use App\Models\Api\TearoomBooking;
use Illuminate\Http\Request;
use Repositories\PaymentRepository;
use Repositories\TearoomBookingRepository;

class BookingController extends ApiController
{
    protected $repository;


    public function __construct( TearoomBookingRepository $repository )
    {
        $this->repository = $repository;

    }


    public function store(TearoomBookingRequest $request,PaymentRepository $paymentRepository){


        if ($model = TearoomBooking::store($request->all())) {
            //和微信支付交互
            if ($model->real_fee)
                $data = $paymentRepository->TearoomUnifiedOrder($model);

            //booking_id
            $data[ 'booking_id' ] = $model->id;

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

            if ($this->bookingRepository->checkRoomCheckInIsUsed($request->booking_id, $model->checkin)) {
                return $this->failed('抱歉,您选的日期前一秒已被使用,请重新选取时间');
            }
            //和微信支付交互
            if ($model->status != 0) {
                return $this->error('error');
            }
            $data = $this->payment->experienceUnifiedOrder($model);

            //booking_id
            $data[ 'booking_id' ] = $model->id;
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
     *
     */
    public function orderStatusToChange( Request $request )
    {
        //授权
        $this->authorize('update', ExperienceBooking::query()->find($request->booking_id));


        if (ExperienceBooking::changeBookingOrder($request->booking_id, $request->status)) {

            return $this->message('success');
        }
        else {
            return $this->failed('fail');
        }
    }


    /**
     * @param Request $request
     * 微信支付回调
     */
    public function miniNotifyCallback( Request $request,PaymentRepository $paymentRepository)
    {

        extract($this->$paymentRepository->experienceNotify());

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