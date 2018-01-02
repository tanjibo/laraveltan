<?php

namespace App\Http\ApiControllers\Art;

use App\Http\ApiControllers\ApiController;
use App\Http\Requests\ArtRequest;
use App\Http\Resources\Art\ArtShowResource;
use App\Http\Resources\Art\CommentResource;
use App\Models\ArtShow;

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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function indexApi(Request $request,ArtShow $art){
        if($request->expectsJson()){
            if(!$art) return [];
            $model = ArtShow::query()->with('comments');

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
                $model->orWhere('id', 'like', "%{$search}%")->orWhere('name', 'like', "%{$search}%");
            }

            $model = $model->paginate($request->prePage ?: 10);
            return response()->json($model);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
       $art=ArtShow::query()->findOrFail($id);


//       return $this->success(CommentResource::collection($comments));

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



}
