<?php

namespace App\Http\Controllers\Experience;

use App\Http\Controllers\Controller;
use App\Models\ExperienceBookingComment;
use App\Models\ExperienceRoom;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        userHasAccess(['experience_comment_show']);
        return view('experience.comment.index');
    }


    public function indexApi( Request $request )
    {
        if (\request()->expectsJson()) {

            $model = ExperienceBookingComment::query()->with('experience_comment_pics', 'user', 'experience_rooms', 'reply');

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
                $model->orWhere('id', 'like', "%{$search}%")->orWhere('customer', 'like', "%{$search}%");
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
        userHasAccess(['experience_comment_show']);
        $room = ExperienceRoom::query()->whereIn('type', [ 1, 2 ])->get();
        $user = homeUser();
        return view('experience.comment.create', compact('room', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        $request[ 'commentable_type' ] = ExperienceRoom::class;
        return response()->json(ExperienceBookingComment::addComment($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( ExperienceBookingComment $experience_comment )
    {
        return response()->json($experience_comment->delete());
    }

    /**
     * @param ExperienceBookingComment $comment
     * @param Request $request
     * 回复
     */
    public function reply( ExperienceBookingComment $experience_comment, Request $request )
    {

        if ($model = $experience_comment->reply()->create($request->all())) {
            $experience_comment->is_reply = true;
            $experience_comment->save();
            return response()->json($model);
        }


    }
}
