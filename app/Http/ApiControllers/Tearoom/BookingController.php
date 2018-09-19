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
use App\Jobs\CloseTearoomBooking;
use App\Models\Api\TearoomBooking;
use App\Models\TearoomPrice;
use App\Models\TearoomSchedule;
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
            CloseTearoomBooking::dispatch($model, config('app.tearoom_order_ttl'))->onQueue(app()->environment() . "_tearoomSm");
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
    public function totalPrice( TearoomPrice $price, Request $request )
    {
        $this->validate(
            $request, [
                        'start_point' => 'required',
                        'date'        => [
                            'required',
                            function( $attribute, $value, $fail ) {
                                if (!strtotime($value)) {
                                    return $fail($attribute . ' is invalid.');
                                }
                            },
                        ],
                    ]
        );
        $end_point = $price->durations > 0 ? $request->start_point + $price->durations : $request->start_point - $price->durations;
        //时间段
        $time      = TearoomSchedule::$timetable[ $request->start_point ] . ' - ' . TearoomSchedule::$timetable[ $end_point ];
        $durations = $price->durations / 2;
        if ($durations > 0) {
            $durations .= '小时';
        }
        else {
            $durations = '夜场';
        }
        return $this->success(
            [
                'tearoom'   => $price->tearoom->name,
                'limits'    => $price->tearoom->limits,
                'durations' => $durations,
                'time'      => $time,
                'date'      => date_format((new \DateTime($request->date)), 'Y年m月d日'),
                'price'     => $price->fee,
            ]
        );

    }

    /**
     * @param Request $request
     * @return mixed
     * 重新支付
     */
    public function repay( Request $request, PaymentRepository $payment )
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
    public function orderList( Request $request )
    {
        return $this->success($this->repository->orderListApi($request->orderStatus));
    }


    /**
     * @param Request $request
     * @return mixed
     *
     */
    public function changeOrderStatus( Request $request, TearoomBooking $booking )
    {
        //授权
        $this->authorize('update', $booking);

        if (TearoomBooking::changeOrderStatusToApi($booking, $request->status)) {
            return $this->message('success');
        }
        else {
            return $this->failed('fail');
        }
    }


}