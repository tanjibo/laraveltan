<?php

namespace App\Http\ApiControllers\Art;

use App\Http\ApiControllers\ApiController;
use App\Http\Requests\ArtCommentRequest;
use App\Http\Resources\Art\ArtShowResource;
use App\Models\ArtShow;
use App\Models\ArtShowComment;
use Illuminate\Http\Request;
use Repositories\ArtShowCommentRepository;

class CommentController extends ApiController
{

    protected $repository;

    public function __construct( ArtShowCommentRepository $repository )
    {
        $this->repository = $repository;
    }


    public function commentList( ArtShow $art_show )
    {
       $this->repository->commentList($art_show);
    }


    public function commentDetail(ArtShowComment $art_comment){

        $this->repository->commentDetail($art_comment);
    }


    /**
     * art_show_id
     * comment
     * parent_id 如果有代表这是一个回复
     * @param ArtCommentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * 添加评论
     */
    public function store( ArtCommentRequest $request )
    {
        //获取登录用户的id
        $request[ 'user_id' ] = auth()->id() ?: 5;
        $model   = ArtShowComment::query()->create($request->all());

        return $model ? $this->success(new ArtShowResource($model)) : $this->error('添加评论错误');

    }


    /**
     * @param Request $request
     * @param $id 评论id
     * @return mixed
     * id
     */
    public function destroy( Request $request, $id )
    {
        $art_comment = ArtShowComment::query()->findOrFail($id);

        return $this->success($art_comment->delete());
    }
}
