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


use App\Foundation\Lib\Payment\TearoomPayment;
use App\Http\ApiControllers\ApiController;
use App\Http\Requests\TearoomBookingRequest;
use App\Jobs\CloseTearoomBooking;
use App\Models\Api\TearoomBooking;
use App\Models\PaymentLog;
use App\Models\TearoomPrice;
use EasyWeChat\Factory;
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



    public function store( TearoomBookingRequest $request, PaymentRepository $paymentRepository )
    {


        if ($model = TearoomBooking::store($request->all())) {
           //异步队列
            $this->dispatch(new CloseTearoomBooking($model, config('app.tearoom_order_ttl')));
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
     * @param TearoomPrice $price
     * @return \Illuminate\Http\JsonResponse
     * 订单价格
     */
    public function totalPrice( TearoomPrice $price )
    {
        return $this->success(
            [
                'tearoom' => $price->tearoom->name,
                'limits'  => $price->tearoom->limits,
                'price'   => $price->fee,
            ]
        );

    }

    /**
     * @param Request $request
     * @return mixed
     * 重新支付
     */
    public function repay( Request $request,PaymentRepository $payment)
    {


        if ($model = TearoomBooking::find($request->booking_id)) {

//            if ($this->repository->checkRoomCheckInIsUsed($request->booking_id, $model->checkin)) {
//                return $this->failed('抱歉,您选的日期前一秒已被使用,请重新选取时间');
//            }
            //和微信支付交互
            if ($model->status != 0) {
                return $this->error('error');
            }
            $data = $payment->tearoomUnifiedOrder($model);

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
    public function orderList(Request $request)
    {
        return $this->success($this->repository->orderListApi($request->orderStatus));
    }


    /**
     * @param Request $request
     * @return mixed
     *
     */
    public function changeOrderStatus( TearoomBooking $booking,$formId )
    {
        //授权
        $this->authorize('update', $booking);


        if (TearoomBooking::changeStatus($booking, TearoomBooking::STATUS_CANCEL)) {
            return $this->message('success');
        }
        else {
            return $this->failed('fail');
        }
    }



}