<?php

namespace App\Http\Controllers\Experience;

use App\Http\Controllers\Controller;
use App\Models\ExperienceArticle;
use Illuminate\Http\Request;

/**
 * Class ProcessController
 * @package App\Http\Controllers\Experience
 * 改造过程
 */
class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        userHasAccess(['experience_article_show']);
        $model=ExperienceArticle::query()->where('type',ExperienceArticle::TYPE_TRANSFORM_PROCESS)->orderBy('created_at','desc')->get();
      return view('experience.article.index',compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        userHasAccess(['experience_article_create']);
        return view("experience.article.create_and_edit");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        $this->validate(
            $request, [
                        'author'    => 'required|max:10', //入住时间
                        'title'     => 'required|max:100', //退房时间
                        'content'   => 'required',           //顾客
                        'cover_img' => 'required',
                    ]
        );

        return response()->json(ExperienceArticle::query()->create($request->all()));
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
    public function edit( ExperienceArticle $experience_article)
    {
        userHasAccess(['experience_article_update']);
        $model=$experience_article;
         return view('experience.article.create_and_edit',compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, ExperienceArticle $experience_article )
    {
        return response()->json($experience_article->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {
        //
    }
}
