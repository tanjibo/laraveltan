<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 8/3/2018
 * Time: 11:09 AM
 */

namespace App\Observer\Experience;


use App\Models\ExperienceArticle;

class ArticleObserver
{
    public function saved( ExperienceArticle $article )
    {

    }

    public function regexImg(string $content)
    {
        preg_match_all('/<img.*?data-src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png|\.jpeg|\.?]))[\'|\"].*?[\/]?>/',$content[0],$images);
    }
}