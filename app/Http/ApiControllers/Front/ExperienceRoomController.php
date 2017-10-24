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

namespace App\Http\ApiControllers\Front;



use App\Http\ApiControllers\ApiController;

use App\Models\ExperienceRoomCommonSetting;
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

    public function roomDetail($id)
    {

        //dump(Auth::id());
        if($data=$this->repository->find($id)){
            return $this->success($data);
        }else{
            return $this->notFound();
        }

    }

    public function question(){
        $data= ExperienceRoomCommonSetting::query()->where('type','question_tip')->value('system_tip');
        return $this->success($data);
    }


}