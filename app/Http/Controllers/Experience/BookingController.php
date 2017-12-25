<?php

namespace App\Http\Controllers\Experience;

use App\Http\Controllers\Controller;
use App\Models\ExperienceBooking;
use App\Models\ExperienceBookingRequirement;
use App\Models\ExperienceRoom;
use App\Observer\Experience\ExperienceBookingObserver;
use App\Repositories\ExperienceBookingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{

    private $bookingRepository;

    public function __construct( ExperienceBookingRepository $bookingRepository )
    {

        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        userHasAccess(['experience_booking_show']);

        return view('experience.booking.index');
    }


    public function indexApi( Request $request )
    {
        if (\request()->expectsJson()) {

            $model = ExperienceBooking::query()->with('experience_booking_rooms', 'user');

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
                $model->orWhere('id', 'like', "%{$search}%")->orWhere('customer', 'like', "%{$search}%");
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
        userHasAccess(['experience_booking_create']);

        $model = ExperienceRoom::query()->findOrFail($request->experience_room);
        return view('experience.booking.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {

        $this->validate(
            $request, [
                        'checkin'  => 'required|date_format:Y-m-d', //入住时间
                        'checkout' => 'required|date_format:Y-m-d', //退房时间
                        'customer' => 'required|max:25',           //顾客
                        'gender'   => 'required',
                        'pay_mode' => 'required', //支付方式
                        'people'   => 'required|max:10|min:1',//人数
                        'rooms'    => 'required|array', //房间id 不能为空
                        'mobile'   => 'bail|required|size:11|regex:/^1[34578][0-9]{9}$/', //电话号码

                    ]
        );

        return response()->json(ExperienceBooking::store($request));


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show( ExperienceBooking $experience_booking)
    {
        userHasAccess(['experience_booking_show']);
        $model          = $experience_booking;
        $user           = $experience_booking->user;
        $room           = $experience_booking->experience_booking_rooms;
        $myRequirements = $experience_booking->experience_booking_requirements()->where('admin_id', Auth()->id())->value('requirements');
        return view('experience.booking.show', compact('model', 'user', 'room', 'myRequirements'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit( ExperienceBooking $experience_booking )
    {
        userHasAccess(['experience_booking_update']);
        $model = ExperienceBooking::query()->findOrFail($experience_booking->id);
        return view('experience.booking.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, ExperienceBooking $experience_booking )
    {

        return response()->json(ExperienceBooking::modify($experience_booking));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {

        return response()->json(ExperienceBooking::query()->findOrFail($id)->delete());

    }

    public function changeStatus( ExperienceBooking $experience_booking )
    {

        return response()->json(ExperienceBooking::changeStatus($experience_booking));
    }


    /**
     * @param Request $request
     * @param ExperienceBooking $booking
     * @return \Illuminate\Http\JsonResponse
     * 修改备注
     */
    public function editRequirements( Request $request, ExperienceBooking $experience_booking )
    {
        $params = [
            'booking_id' => $experience_booking->id,
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

        return response()->json(ExperienceBookingRequirement::query()->updateOrCreate($params, $data));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 日历的不可用时间
     */
    public function calendarInit( Request $request )
    {
        $disable = $request->checkin ? $this->bookingRepository->roomCheckoutDisableApi() : $this->bookingRepository->roomCheckinDisableApi();

        //如果是修改订单的时候，要把当前订单的日期去掉，不然导致修改订单的时候出现没有办法选下单时候的日期
        if ($request->booking_id) {
            $currentBooking = ExperienceBooking::query()->select('checkin', 'checkout')->where('id', $request->booking_id)->first();
            $data           = splitDateMore($currentBooking->checkin, $currentBooking->checkout);

            $disable = collect($disable)->diff($data);
        }
        return response()->json($disable);
    }


    /**
     * 剩余可以预订的房间
     * @return mixed
     */
    public function leftCheckinRoom( Request $request, ExperienceRoom $experience_room )
    {
        $request[ 'room_id' ] = $experience_room->id;

        $this->validate(
            $request, [
                        'room_id'  => 'required|numeric|max:10|min:1', //房间id 不能为空
                        'checkin'  => 'required|date_format:Y-m-d',
                        'checkout' => 'required|date_format:Y-m-d',
                    ]
        );

        return response()->json($this->bookingRepository->leftCheckinRoomApi());

    }


}
