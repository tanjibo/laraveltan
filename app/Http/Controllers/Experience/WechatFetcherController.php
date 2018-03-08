<?php

namespace App\Http\Controllers\Experience;

use App\Foundation\Lib\WechatArticleFetcher;
use App\Http\Controllers\Controller;
use App\Models\ExperienceArticle;
use Illuminate\Http\Request;

class WechatFetcherController extends Controller
{
    function index()
    {
        userHasAccess([ 'experience_more_article_show' ]);
        $model = ExperienceArticle::query()->where('type', ExperienceArticle::TYPE_WECHAT_FETCHER)->get();
        return view('experience.wechat_fetcher.index', compact('model'));
    }

    function create()
    {
        userHasAccess([ 'experience_more_article_create' ]);
        return view('experience.wechat_fetcher.create');
    }


    function store( Request $request )
    {
        userHasAccess([ 'experience_article_show' ]);
        set_time_limit(60 * 10);
        $url = $request->wechat_url;
        if (!$url) abort(500);

        $article = WechatArticleFetcher::get($url);
        return response()->json(ExperienceArticle::query()->create($article));
    }


    function edit( Request $request, ExperienceArticle $experience_wechat_fetcher )
    {
        userHasAccess([ 'experience_article_show' ]);
        $model = $experience_wechat_fetcher;
        $other = ExperienceArticle::query()->wechat()->whereKeyNot($model->id)->get();
        return view('experience.wechat_fetcher.edit', compact('model', 'other'));
    }

    function update( Request $request, ExperienceArticle $experience_wechat_fetcher )
    {
        $experience_wechat_fetcher->type = $request->type;
        if (isset($request->others) && count($request->others)) {
            $models = ExperienceArticle::query()->whereIn('id', $request->others)->get();
            $models->map(
                function( $item ) {
                    $item->type      = ExperienceArticle::TYPE_CUSTOMER_STORY_CHILD;
                    $item->parent_id = request()->id;
                    $item->save();
                }
            );
        }
        return response()->json($experience_wechat_fetcher->save());
    }
}
