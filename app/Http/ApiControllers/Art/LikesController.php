<?php

namespace App\Http\ApiControllers\Art;


use App\Http\ApiControllers\ApiController;
use App\Models\ArtShowCommentLike;
use Illuminate\Http\Request;

class LikesController extends ApiController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 点赞
     */
    public function index(){

    }


    public function store( Request $request )
    {

        $request[ 'user_id' ] = app()->environment() == 'local' ? 165 : auth()->id();

        $model = ArtShowCommentLike::toggle($request->all());

        if (is_bool($model)) {
            return $this->success([ 'message' => '取消点赞成功' ]);
        }
        return $this->success($model);


    }
}
