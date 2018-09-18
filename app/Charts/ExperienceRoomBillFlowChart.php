<?php

namespace App\Charts;

use App\Models\ExperienceBooking;
use ConsoleTVs\Charts\Classes\Echarts\Chart;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExperienceRoomBillFlowChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    protected $allOrderData = null;
    public $totalBillNum;
    public $totalCompleteOrderCount;

    public function __construct()
    {
        parent::__construct();
         $this->getAllOrder();
    }

    public function __clone()
    {
        parent::__construct();

    }

    private function getAllOrder()
    {
        $this->allOrderData = ExperienceBooking::query()->withoutGlobalScope(new SoftDeletingScope())->get();
        $order=$this->allOrderData->where('status', 10);
        $this->totalCompleteOrderCount=$order->count();
        $this->totalBillNum=$order->sum('real_price');

    }

    public function barChart()
    {
        $allOrder = $this->allOrderData->where('status', 10)->groupBy(
            function( $item ) {
                return date_format($item->created_at, 'Y-m');
            }
        )->map(
            function( $item, $key ) {
                return [
                    'realPrice' => $item->sum('real_price'),
                    'price'     => $item->sum('price'),
                    'date'      => $key,
                ];
            }
        )
        ;

        $date       = $allOrder->pluck('date');
        $real_price = $allOrder->pluck('realPrice');
        $price      = $allOrder->pluck('price');
        $this->labels($date->toArray())->options(
            [
                'title'   => [ 'text' => '安吉订单流水汇总' ],
                'tooltip' => [
                    'trigger'     => 'axis',
                    'axisPointer' => [
                        'type'  => 'cross',
                        'label' => [
                            'backgroundColor' => '#6a7985',
                        ],
                    ],
                ],

            ]
        )
        ;
        $this->dataset("每月应付总金额", 'bar', $price)->options(
            [
                'color'  => 'red',
                'barGap' => 0,
            ]
        )
        ;

        $this->dataset('每月实付总金额', 'bar', $real_price)->options(
            [
                'color'  => 'green',
                'barGap' => 0,
            ]
        )
        ;
        return $this;
    }

    public function lineChart()
    {
        $allOrder = $this->allOrderData->groupBy(
            function( $item ) {
                return date_format($item->created_at, 'Y-m');
            }
        )->map(
            function( $item, $key ) {
                return [
                    'total'    => $item->count(),
                    'complete' => $item->where('status', ExperienceBooking::STATUS_COMPLETE)->count(),
                    'paid'     => $item->where('status', ExperienceBooking::STATUS_PAID)->count(),
                    'cancel'   => $item->where('status', ExperienceBooking::STATUS_CANCEL)->count(),
                    'date'     => $key,
                ];

            }
        )
        ;
        $date     = $allOrder->pluck('date');
        $complete = $allOrder->pluck('complete');
        $cancel   = $allOrder->pluck('cancel');
        $paid     = $allOrder->pluck('paid');
        $total    = $allOrder->pluck('total');

        $this->labels($date->toArray())->options(
            [
                'title'   => [ 'text' => '安吉订单数量汇总' ],
                'tooltip' => [
                    'trigger'     => 'axis',
                    'axisPointer' => [
                        'type'  => 'cross',
                        'label' => [
                            'backgroundColor' => '#6a7985',
                        ],
                    ],
                ],

            ]
        )
        ;
        $this->dataset("每月总订单数", 'line', $total);
        $this->dataset("每月取消数", 'line', $cancel);
        $this->dataset("每月完成数", 'line', $complete);
        $this->dataset("每月支付数", 'line', $paid);


        return $this;

//        $allOrder = ExperienceBooking::query()->withoutGlobalScope(new SoftDeletingScope())->where('status', 10)->groupBy(DB::raw("(DATE_FORMAT(created_at,'%Y-%m'))"))->select(DB::raw("count(id) as total_num,sum(real_price) as real_price,sum(price) as price,(DATE_FORMAT(created_at,'%Y-%m')) as date"))->orderBy('created_at', 'desc')->limit(6)->get();

    }


}
