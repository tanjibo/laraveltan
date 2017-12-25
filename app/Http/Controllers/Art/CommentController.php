<?php

namespace App\Http\Controllers\Art;

use App\Models\ArtShow;
use App\Models\ArtShowComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index()
    {
        userHasAccess(['art_comment_show']);
        return view('art.comment.index');
    }

    public function indexApi(Request $request,ArtShowComment $art_comment){
        if($request->expectsJson()){
            if(!$art_comment) return [];
            $model = ArtShowComment::query()->with('art_show','owner');

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

    public function create( Request $request )
    {
        userHasAccess(['art_comment_create']);
        $art  = ArtShow::query()->get();
        $user = homeUser();
        return view('art.comment.create', compact('art', 'user'));
    }

    public function store( Request $request )
    {
        if ($request->expectsJson()) {
           return response()->json(ArtShowComment::query()->create($request->all()));
        }

        ArtShowComment::query()->create($request->all());
        return redirect()->back();
    }

    public function destroy( Request $request, ArtShowComment $art_comment )
    {
        $art_comment->delete();
    }
}
