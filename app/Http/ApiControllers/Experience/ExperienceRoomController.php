<?php
/**
 * |--------------------------------------------------------------------------
 * |安吉体验中心房间
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 28/9/2017
 * Time: 10:29 AM
 */

namespace App\Http\ApiControllers\Experience;



use App\Http\ApiControllers\ApiController;

use App\Models\ExperienceRoomCommonSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Repositories\ExperienceRoomRepository;



class ExperienceRoomController extends ApiController
{
    protected  $repository;
    public function __construct( ExperienceRoomRepository $repository )
    {
        $this->repository = $repository;
    }

    /**
     * @return mixed
     * 房间列表
     */
    public function roomList()
    {

        if($data=$this->repository->all()){

            return $this->success($data);
        }else{
            return $this->notFound();
        }
    }

    public function roomDetail(Request $request,$room_id)
    {

        $request['room_id']=$room_id;

        $this->validate($request,[
           'room_id'=>'required|numeric|max:10|min:1' //房间id 不能为空
        ]);


        if($data=$this->repository->find($room_id)){
            return $this->success($data);
        }else{
            return $this->notFound();
        }

    }

    /**
     * @return mixed
     * 常见问题
     */
    public function question(){
        $data= ExperienceRoomCommonSetting::query()->where('type','question_tip')->value('system_tip');
        return $this->success($data);
    }


}