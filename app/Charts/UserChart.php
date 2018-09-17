<?php

namespace App\Charts;

use App\Models\User;
use ConsoleTVs\Charts\Classes\Echarts\Chart;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    protected $allUserData;

    public function __construct()
    {
        parent::__construct();
        $this->getAllUser();
    }

    private function getAllUser()
    {
        $this->allUserData = User::query()->withoutGlobalScope(new SoftDeletingScope())->get();
    }

    public function lineChart()
    {
        $allOrder   = $this->allUserData->groupBy(
            function( $item ) {
                return date_format($item->created_at, 'Y-m');
            }
        )->map(
            function( $item, $key ) {
                return [
                    'total'      => $item->count(),
                    'mall'       => $item->where('source', User::SOURCE_DEFAULT)->count(),
                    'tearoom'    => $item->where('source', User::SOURCE_TEAROOM)->count(),
                    'experience' => $item->where('source', User::SOURCE_EXPERIENCEROOM)->count(),
                    'artShow'    => $item->where('source', User::SOURCE_ARTSHOW)->count(),
                    'date'       => $key,
                ];

            }
        )
        ;
 
        $date       = $allOrder->pluck('date');
        $mail       = $allOrder->pluck('mall');
        $tearoom    = $allOrder->pluck('tearoom');
        $experience = $allOrder->pluck('experience');
        $artShow    = $allOrder->pluck('artShow');
        $total      = $allOrder->pluck('total');

        $this->labels($date->toArray())->options(
            [
                'title'   => [ 'text' => '用户汇总' ],
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

        $this->dataset("总增加人数", 'line', $total);
        $this->dataset("商城", 'line', $mail);
        $this->dataset("茶舍", 'line', $tearoom);
        $this->dataset("艺术空间", 'line', $artShow);
        $this->dataset("安吉", 'line', $experience);

        return $this;

    }
}
