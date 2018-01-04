<?php

namespace App\Http\Controllers\Art;


use App\Models\ArtShow;
use App\Models\ArtShowComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Repositories\ArtShowLikeAndCollectionRepository;

class LikesController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 点赞
     */

    public $repository='';
    public function __construct(ArtShowLikeAndCollectionRepository $repository) {
        $this->repository=$repository;
    }

    public function store( Request $request )
    {

        $request[ 'user_id' ] = Auth()->id();
        if($request->type=='art_show'){
          $model=  ArtShow::query()->find($request->id);
           return response()->json($this->repository->artShowLike($model));
        }else{

            $model=ArtShowComment::query()->find($request->id);

            return response()->json($this->repository->artShowCommentLike($model));

        }
    }



}
