<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 8/3/2018
 * Time: 1:49 PM
 */

namespace App\Foundation\Lib;


use App\Models\ExperienceArticle;

class WechatArticleFetcher
{

    static function get( string $url )
    {
        //获取微信文章内容
        $file = file_get_contents($url);
        if (!$file) {
            throw new \Error("文章地址有错误");
        }
        $article = [];
        //文章标题
        preg_match('/<title>(.*?)<\/title>/', $file, $title);
        $article[ 'title' ] = $title ? $title[ 1 ] : '';
        //文章暂时正文，因为图片还是微信地址
        preg_match('/<div class="rich_media_content " id="js_content">[\s\S]*?<\/div>/', $file, $content);
        //匹配出所有地址，后期应该把这个改成队列
        preg_match_all('/<img[\s\S]*?data-src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/', $content[ 0 ], $images);
        //如果有图片替换图片
        $newContent = '';
        if ($images[ 1 ]) {

            $dealImg = static::replaceImg($images[ 1 ]);
            if (count($dealImg[ 'new' ])) {
                $newContent             = str_replace($dealImg[ 'old' ], $dealImg[ 'new' ], $content[ 0 ]);
                $article[ 'cover_img' ] = $dealImg[ 'new' ][ 0 ];
            }
        }
        $article[ 'content' ]    = $newContent ?: $content[ 0 ];
        $article[ 'author' ]     = 'weaving';
        $article[ 'wechat_url' ] = $url;
        $article[ 'type' ]       = ExperienceArticle::TYPE_WECHAT_FETCHER;
        return $article;
    }

    private static function replaceImg( array $img )
    {
        // 储存原地址和下载后地址
        $old = [];
        $new = [];
        // 去除重复图片地址
        if (count($img)) {

            foreach ( $img as $v ) {
                $filename = Qiniu::fetchOne($v);
                if ($filename) {
                    // 图片保存成功 替换地址
                    $old[] = $v;
                    $new[] = $filename;
                }
                else {
                    // 失败记录日志
                    continue;
                }
            }
            $old[] = 'data-src';
            $new[] = 'src';
            // $new_content = str_replace($old, $new, $content[ 0 ]);
            return [ 'new' => $new, 'old' => $old ];
        }
        return [ 'new' => '', 'old' => '' ];
    }
}