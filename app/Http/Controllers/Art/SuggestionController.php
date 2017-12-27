<?php

namespace App\Http\Controllers\Art;

use App\Http\ApiControllers\ApiController;
use App\Models\ArtShowSuggestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SuggestionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('art.suggestion.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function indexApi( Request $request)
    {
        if ($request->expectsJson()) {
            $model = ArtShowSuggestion::query()->with('user');
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
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( ArtShowSuggestion $art_suggestion, Request $request )
    {
        if ($request->expectsJson()) {

            $art_suggestion->delete();
            return response()->json([]);
        }
    }


    /**
     * @param ArtShowSuggestion $art_suggestion
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 回复用户建议
     */
    public function reply( ArtShowSuggestion $art_suggestion, Request $request )
    {
        if ($request->expectsJson()) {
            $art_suggestion->reply = $request->reply;
            $art_suggestion->save();
            return response()->json($art_suggestion);
        }
    }
}
