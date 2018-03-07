<?php

namespace App\Http\Controllers\Experience;

use App\Http\Controllers\Controller;
use App\Models\ExperienceArticle;
use Illuminate\Http\Request;

class MoreArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( ExperienceArticle $model )
    {
        userHasAccess(['experience_more_article_show']);
        $model = ExperienceArticle::query()->where('type', ExperienceArticle::TYPE_CUSTOMER_STORY_FATHER)->with('articleChild')->orderBy('created_at', 'desc')->get();
        return view('experience.more_article.index', compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        userHasAccess(['experience_more_article_create']);
        return view('experience.more_article.create_and_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request, ExperienceArticle $model )
    {
        $child = collect($request->data)->where('type', $model::TYPE_CUSTOMER_STORY_CHILD)->toArray();

        $father = collect($request->data)->filter(
            function( $item ) use ( $model ) {
                if ($item[ 'type' ] == $model::TYPE_CUSTOMER_STORY_FATHER) return $item;
            }
        )->toArray()
        ;
        $e      = ExperienceArticle::store(current($father));
        if ($e && count($child)) {
            $e->articleChild()->createMany($child);
        }
        return response()->json([ 'message' => "success", 'status' => 200 ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {

        return view('experience.more_article.create_and_edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit( ExperienceArticle $experience_more_article )
    {
        userHasAccess(['experience_more_article_update']);
        $child   = $experience_more_article->articleChild->toArray();
        $model[] = $experience_more_article->toArray();

        if (count($child)) {
            $model = array_merge_recursive($model, $child);
        }

        $model = collect($model);

        return view('experience.more_article.create_and_edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, ExperienceArticle $experience_more_article )
    {
        $father = $request->data[ 0 ];
        $child  = collect($request->data)->where('type', $experience_more_article::TYPE_CUSTOMER_STORY_CHILD)->toArray();

        if ($experience_more_article->update($father) && count($child)) {
            foreach ( $child as $v ) {
                if(isset($v['id'])){
                    ExperienceArticle::query()->where('id',$v['id'])->update($v);
                }else{
                    $experience_more_article->articleChild()->create($v);
                }
            }
        }
        return response()->json(['status'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( ExperienceArticle $experience_more_article)
    {
        return response()->json($experience_more_article->delete());
    }
}
