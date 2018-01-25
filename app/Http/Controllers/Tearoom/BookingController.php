<?php

namespace App\Http\Controllers\Tearoom;

use App\Models\Tearoom;
use App\Models\TearoomBooking;
use App\Models\TearoomBookingRequirement;
use App\Models\TearoomSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('tearoom.booking.index');
    }


    public function indexApi( Request $request )
    {
        /**
         * @param Request $request
         * @return \Illuminate\Http\JsonResponse
         *
         */
        if ($request->expectsJson()) {

            $model = TearoomBooking::query()->with('tearoom');
            //排序
            if ($order = $request->columns ?: 'id') {
                $request->order == 'ascending' ? $model->orderBy($request->columns) : $model->orderByDesc($request->columns);
            }

            //选择框的检索
            if ($select = $request->select) {
                $model->orWhere($select);
            }
            //输入框的检索
            if ($search = $request->search) {
                $model->orWhere('id', 'like', "%{$search}%")->orWhere('customer', 'like', "%{$search}%")->orWhere('mobile', 'like', "%{$search}%");
            }

            $model = $model->paginate($request->prePage ?: 10);
            return response()->json($model);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        $tearoom = Tearoom::query()->with('tearoom_prices')->find($request->tearoom);

        $timeSchedule = collect(TearoomSchedule::$timetable);
        dd($tearoom->tearoom_prices[0]->toArray());exit;
        return view('tearoom.booking.create_and_edit', compact('timeSchedule', 'tearoom'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 初始化时间
     */
    public function getInitTimeTable( Request $request )
    {
        if (request()->expectsJson()) {

            $date = $request->date ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');

            return response()->json(TearoomSchedule::getTimetable($request->price_id, $date));
        }
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

            $request[ 'date' ] = date('Y-m-d', strtotime($request->date));
            return response()->json(TearoomBooking::query()->create($request->all()));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show( TearoomBooking $tearoom_booking )
    {
        $booking        = $tearoom_booking;
        $myRequirements = $booking->tearoom_booking_requirements()->where('admin_id', Auth()->id())->value('requirements');
        return view('tearoom.booking.show', compact('booking', 'myRequirements'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( TearoomBooking $tearoom_booking,Request $request )
    {
        if($request->expectsJson()){
            $tearoom_booking->delete();
            return response()->json([]);
        }
    }


    /**
     * @param TearoomBooking $booking
     * @return \Illuminate\Http\JsonResponse
     * 修改状态
     */
    public function changeStatus( TearoomBooking $booking )
    {
        return response()->json(TearoomBooking::changeStatus($booking));
    }


    /**
     * @param Request $request
     * @param TearoomBooking $booking
     * @return \Illuminate\Http\JsonResponse
     * 添加备注
     */
    public function editRequirements( Request $request, TearoomBooking $booking )
    {
        $params = [
            'booking_id' => $booking->id,
            'admin_id'   => Auth::id(),
        ];
        $data   = array_merge(
            $params, [
                       'requirements' => $request->requirements,
                       'type'         => 1, //以前有多个不同类型的房间，现在只有一种，就默认为1 就好了
                       'event'        => '修改备注--' . Auth::user()->nickname,
                       'handler'      => Auth::user()->nickname,
                   ]
        );

        return response()->json(TearoomBookingRequirement::query()->updateOrCreate($params, $data));
    }



}
