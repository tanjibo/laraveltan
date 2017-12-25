<?php

namespace App\Http\Controllers\Art;


use App\Models\ArtShowCommentLike;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikesController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 点赞
     */
    public function store( Request $request )
    {

        $request[ 'user_id' ] = Auth()->id();
        return response()->json(ArtShowCommentLike::toggle($request->all()));

    }
}
