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
        if($data=$this->repository->find($id)){
            return $this->success($data);
        }else{
            return $this->notFound();
        }

    }


}