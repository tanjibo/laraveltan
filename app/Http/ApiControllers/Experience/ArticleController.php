<?php

namespace App\Http\ApiControllers\Experience;

use App\Http\ApiControllers\ApiController;
use App\Http\Resources\Experience\ArticleResource;
use App\Models\ExperienceArticle;
use Illuminate\Http\Request;


class ArticleController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $article = ExperienceArticle::query()->where('type', ExperienceArticle::TYPE_TRANSFORM_PROCESS)->orderBy("created_at", 'desc')->first();
        if (!count($article)) {
            return $this->success([ 'data' => '', 'message' => '', 'code' => 404 ]);
        }
        return $this->success(new ArticleResource($article));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list( Request $request )
    {
        $request->type = ExperienceArticle::TYPE_CUSTOMER_STORY_FATHER;

        $article = ExperienceArticle::query()->with('articleChild')->where('type', ExperienceArticle::TYPE_CUSTOMER_STORY_FATHER)->orderBy("created_at", 'desc')->first();
        if (!count($article)) {
            return $this->success([ 'data' => '', 'message' => '', 'code' => 404 ]);
        }

        return $this->success(new ArticleResource($article));
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id, Request $request )
    {
        $model           = ExperienceArticle::query()->findOrFail($id);
        $request->detail = true;
        return $this->success(new ArticleResource($model));
    }


}
