<?php

namespace App\Http\Controllers\Art;

use App\Http\Requests\ArtRequest;
use App\Models\ArtShow;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArtShowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ArtShow $art)
    {
        userHasAccess(['art_show']);
        return view('art.artshow.index');
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        userHasAccess(['art_create']);
        return view('art.artshow.create_and_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArtRequest $request)
    {
       return response()->json(ArtShow::query()->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ArtShow $art,Request $request)
    {
        userHasAccess(['art_show']);
       $comments=$art->comments()->where('parent_id',0)->with(['owner','childs','likes'=>function($query){
           $query->where('user_id',auth()->id());
       }])->get();




       $userLike=User::query()->whereIn('id',$art->likes()->pluck('user_id'))->pluck('avatar','id');

      $isAuthUserLike=$userLike->has(auth()->id());

      $userCollect=User::query()->whereIn('id',$art->collections()->pluck('user_id'))->pluck('avatar','id');
        $isAuthUserCollect=$userCollect->has(auth()->id());

       return view('art.artshow.show',compact('comments','art','userLike','isAuthUserLike','isAuthUserCollect'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ArtShow $art,Request $request)
    {

        userHasAccess(['art_update']);
        return view('art.artshow.create_and_edit',compact('art'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArtRequest $request, ArtShow $art)
    {
        return response()->json($art->update($request->all()));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ArtShow $art,Request $request)
    {
        if($request->expectsJson()){

           $flag= $art->delete();

           return response()->json([]);

        }
    }
}
