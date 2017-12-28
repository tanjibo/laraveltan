<?php

namespace App\Http\Controllers\Experience;

use App\Models\Backend\ExperienceRoom;
use App\Models\Backend\ExperienceRoomCommonSetting;
use App\Models\Backend\ExperienceRoomLockdate;
use Repositories\ExperienceRoomLockDateRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Repositories\ExperienceRoomRepository;

class RoomController extends Controller
{

    private $roomRepository;

    public function __construct( ExperienceRoomRepository $roomRepository )
    {
        $this->roomRepository = $roomRepository;
    }

    public function index()
    {
        userHasAccess([ 'experience_room_show' ]);
        $model = ExperienceRoom::query()->active()->get();
        return view('experience.room.index', compact("model"));
    }

    public function show( ExperienceRoom $room )
    {

    }

    public function create()
    {
        userHasAccess([ 'experience_room_create' ]);

        $attachUrl = ExperienceRoomCommonSetting::query()->where('type', 'supporting_url')->get();

        return view('experience.room.create_and_edit', compact('attachUrl'));
    }

    /**
     * @param Request $request
     * 添加房间
     */
    public function store( Request $request )
    {
        if ($request->expectsJson()) {
            $model = ExperienceRoom::query()->create($request->all());
            if ($model) {
                if ($request->sliders) {
                    $model->sliders()->createMany($request->sliders);
                }
                return response()->json($model);
            }
            return response()->json([],501);
        }

    }

    public function edit( ExperienceRoom $experience_room )
    {
        userHasAccess([ 'experience_room_update' ]);
        if (!$experience_room) abort(401);
        $model = $experience_room;
        //配套设施图片

        $attachUrl = ExperienceRoomCommonSetting::query()->where('type', 'supporting_url')->select('url','id')->get();
        $specialPrice = $model->experience_special_price;


        return view('experience.room.create_and_edit', compact('model', 'attachUrl', 'specialPrice'));
    }

    /**
     * @param Request $request
     * @param ExperienceRoom $room
     * 更新房间
     */
    public function update( Request $request, ExperienceRoom $experience_room )
    {
        if ($request->expectsJson()) {
            return response()->json($this->roomRepository->updateRoom($request, $experience_room));

        }
    }

    /**
     * @param Request $request
     * @param ExperienceRoom $room
     * @return \Illuminate\Http\JsonResponse
     * 生成价格
     */
    public function makePrice( Request $request, ExperienceRoom $experience_room )
    {

        return response()->json($this->roomRepository->makePrice($request, $experience_room));
    }


    /**
     * 锁定时间
     */
    public function lockDate( Request $request, $id )
    {
        userHasAccess([ 'experience_room_date_lock' ]);

        if ($request->method() == 'POST') {

            $lockDate = collect($request->lockDate)->merge($request->selfDate)->flatten(2)->filter(
                function( $item ) {
                    return $item >= date('Y-m-d');
                }
            )
            ;

            $model = ExperienceRoomLockdate::query()->updateOrCreate([ 'room_id' => $request->room_id ], [ 'lockdate' => $lockDate ]);

            return response()->json($model);
        }

        $lockDate = collect(ExperienceRoomLockDateRepository::initLockDate($id));

        return view('experience.room.lockDate', compact('lockDate', 'id'));
    }


    public function makeDate( Request $request )
    {

        return collect(splitDateMore($request->startDate, $request->endDate));
    }

}
