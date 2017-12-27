<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 27/12/2017
 * Time: 2:40 PM
 */

namespace App\Observer\Art;


use App\Models\ArtShowSuggestion;

class ArtShowSuggestionObserver
{
    public function creating( ArtShowSuggestion $suggestion )
    {
        //过滤
        $suggestion->content = clean($suggestion->content, 'user_topic_body') ?: '无效建议';
    }

    public function created( ArtShowSuggestion $suggestion )
    {
        $user = auth()->user();
        if ($suggestion->mobile && !$user->mobile) {
            $user->mobile = $suggestion->mobile;
            $user->save();
        }
    }
}