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
        $data=ArtShow::query()->orderBy('created_at','DESC')->limit(10)->get();

        return $this->success(ArtShowResource::collection($data));
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
       $comments=$art->Comments()->with(['owner','replies'])->get();
       return $this->success(CommentResource::collection($comments));

    }



}
