<?php

namespace App\Http\ApiControllers\Art;


use App\Http\ApiControllers\ApiController;
use App\Models\ArtShow;
use App\Models\ArtShowComment;
use App\Models\ArtShowCommentLike;
use Illuminate\Http\Request;
use Repositories\ArtShowLikeAndCollectionRepository;

class LikesController extends ApiController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 点赞
     */
    protected $repository;

    public function __construct( ArtShowLikeAndCollectionRepository $repository )
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     *
     */
    public function store( Request $request )
    {

        if ($request->type == 'art_show') {
            $model = ArtShow::query()->find($request->id);
            return $this->success($this->repository->artShowLike($model));
        }
        else {

            $model = ArtShowComment::query()->find($request->id);

            return $this->success($this->repository->artShowCommentLike($model));

        }
    }
}
