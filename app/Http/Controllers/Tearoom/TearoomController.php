<?php

namespace App\Http\Controllers\Tearoom;

use App\Models\Tearoom;
use App\Models\TearoomSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TearoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      userHasAccess(['tearoom_show']);
        $model = Tearoom::query()->orderBy('sort', 'ASC')->get();

        return view('tearoom.tearoom.index', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        userHasAccess(['tearoom_create']);
        $timeSchedule = collect(TearoomSchedule::$timetable);

        return view('tearoom.tearoom.create_and_edit', compact('timeSchedule'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        if ($request->expectsJson()) {
            return response()->json(Tearoom::store($request));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Tearoom $tearoom, Request $request )
    {

        $timeSchedule = collect(TearoomSchedule::$timetable);
        $prices       = $tearoom->tearoom_prices()->get();
        return view('tearoom.tearoom.create_and_edit', compact('timeSchedule', 'tearoom', 'prices'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update( Tearoom $tearoom, Request $request )
    {
        if ($request->expectsJson()) {
            return response()->json(Tearoom::modify($tearoom, $request));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Tearoom $tearoom )
    {

        //删除价格
        $tearoom->tearoom_prices()->forceDelete();
        //删除时间表
        $tearoom->tearoom_schedule()->delete();

        $tearoom->delete();
        return redirect()->route('tearoom.index');
    }


    /**
     * 锁定时间
     */
    public function lockDate( Request $request, $id )
    {
        if ($request->method() == "POST") {

            //新添加的
            $lockDateArr = $request->lockDate ?: [];
            //已有的
            $hasLockDateArr = $request->hasLock ?: [];

            $date = collect($lockDateArr)->merge($hasLockDateArr);

            if (!count($date)) response()->json([ 'status' => 1, 'message' => 'success' ]);
            //删除
            TearoomSchedule::query()->where('tearoom_id', $id)->delete();

            collect($date)->map(
                function( $item ) use ( $id ) {
                    $points = TearoomSchedule::getInitSchedule();
                    if (isset($item[ 'selectDate' ])) {
                        foreach ( $item[ 'selectDate' ] as $v ) {
                            $points[ $v ] = 0;
                        }
                    }

                    if (TearoomSchedule::query()->where('tearoom_id', $id)->where('date', $item[ 'date' ])->first()) {
                        TearoomSchedule::query()->where('tearoom_id', $id)->where('date', $item[ 'date' ])->update([ 'points' => $points ]);
                    }
                    else {
                        TearoomSchedule::query()->create(
                            [
                                'tearoom_id' => $id,
                                'date'       => $item[ 'date' ],
                                'points'     => $points,
                            ]
                        )
                        ;
                    }
                }
            );

            return response()->json([ 'status' => 1, 'message' => 'success' ]);
        }

        return view('tearoom.tearoom.lockDate', compact('lockDate', 'id'));
    }


    public function makeDate( Request $request )
    {
        if ($request->expectsJson()) {
            $selectDate = range(0, 47); //生成0-47个数字

            $date = splitDateMore($request->startDate, $request->endDate);
            $date = collect($date)->filter(
                function( $item ) {
                    return $item >= date('Y-m-d');
                }
            )->map(
                function( $item ) use ( $selectDate ) {

                    return [ 'selectDate' => $selectDate, 'date' => $item ];
                }
            )
            ;
            $data = [ 'date' => $date, 'schedule' => TearoomSchedule::$timetable ];

            return response()->json([ 'status' => 0, 'message' => '删除失败', 'data' => $data ]);
        }
    }

    /**
     * 获得初始化锁定数据
     */
    function initGetLockDate(Request $request, $id )
    {
        if ($request->expectsJson()) {
            $lockDate = TearoomSchedule::query()->where('tearoom_id', $id)->where('date', '>=', date('Y-m-d'))->orderBy('date')->get();


            $lockDate = collect($lockDate)->map(
                function( $item ) {
                    $points     = $item->points;
                    $selectDate = [];
                    for ( $i = 0; $i < 48; $i++ ) {
                        if (!$points[ $i ]) array_push($selectDate, $i);
                    }

                    return [ 'date' => $item->date, 'selectDate' => $selectDate ];
                }
            );

            $data = [ 'date' => $lockDate, 'schedule' => TearoomSchedule::$timetable ];
            return response()->json([ 'status' => 1, 'message' => 'success', 'data' => $data ]);
        }

    }
}
