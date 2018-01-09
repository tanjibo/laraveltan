<?php

namespace App\Http\ApiControllers\Art;

use App\Http\ApiControllers\ApiController;
use App\Http\Requests\ArtRequest;
use App\Http\Resources\Art\ArtShowResource;
use App\Http\Resources\Art\CommentResource;
use App\Models\ArtShow;

use App\Models\User;
use Illuminate\Http\Request;


class ArtShowController extends ApiController
{

    public function index()
    {
        $data=ArtShow::query()->orderBy('created_at','DESC')->paginate(10);

        $links=['current_page'=>$data->currentPage(),'total'=>$data->lastPage()];

        $artList = ArtShowResource::collection($data);
        // 标记为已读，未读数量清零
        return  $this->success(['data'=>$artList,'link'=>$links]);
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
       //展品信息
       $art=ArtShow::query()->with(['likes'=>function($query){

           $query->where('user_id',auth()->id())->count();

       },'collections'=>function($query){

           $query->where('user_id',auth()->id())->count();

       }])->findOrFail($id);
//       dd($art->toArray());

       //评论信息
        $comments=$art->comments()->where('parent_id',0)->with(['owner','likes'=>function($query){
            $query->where('user_id',auth()->id());
        }])->limit(10)->get();

         $data=['art'=>new ArtShowResource($art),'comment'=>CommentResource::collection($comments)];

         $art->increment('view_count',1);
       return $this->success($data);

    }

    /**
     * @param Request $request
     * @param ArtShow $art_show
     * @return mixed
     * 获取详情评论
     */
    public function getArtShowComment(Request $request,ArtShow $art_show){

        $comments=$art_show->Comments()->with(['owner','replies'])->paginate(10);

        $links=['current_page'=>$comments->currentPage(),'total'=>$comments->lastPage()];

        $data = CommentResource::collection($comments);


        return  $this->success(['data'=>$data,'link'=>$links]);
    }

    /**
     * @param Request $request
     * @param ArtShow $art_show
     * @return mixed
     * 分享
     */
    public function shareArtShow(Request $request,ArtShow $art_show){
       $model=$art_show->increment('share_count',1);
        return $this->success(['data'=>$model]);
    }



}
