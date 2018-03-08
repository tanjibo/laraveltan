<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 8/3/2018
 * Time: 11:53 AM
 */

namespace App\Foundation\Lib;


use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise\Promise;
use function GuzzleHttp\Promise\unwrap;
use GuzzleHttp\Psr7\Request;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Qiniu
{

    // 需要填写你的 Access Key 和 Secret Key
    private static $accessKey = 'KlJYnXDYLIwwABs6zoPmRGVc6xbAP4Kuzifr-fxM';

    private static $secretKey = 'UR4jTeUYIyClk51yUQ3XGYPoG8XMsimTY5uLvvVA';

    // 要上传的空间
    private static $bucket = 'experience-room';

    private static $picPrefix = '/experience/wechat-fetch/';

    public static $url = 'https://static.liaorusanshe.com/';

    /**
     * @param string $data 二进制数据
     * @param string $filename 上传图片的文件名称
     * @return array 上传图片到七牛
     */
    static function upload( $data = '', $filename = '' )
    {
        $auth  = new Auth(static::$accessKey, static::$secretKey);
        $token = $auth->uploadToken(static::$bucket);

        if ($filename)
            $filename = static::$picPrefix . $filename;
        else
            $filename = static::$picPrefix . date("Ym") . "/" . substr(md5(time() . mt_rand(10, 99)), 0, 10) . ".png";


        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

        list(
            $ret, $err
            )
            = $uploadMgr->put($token, $filename, $data);


        if ($err !== null) {
            return '';
        }
        else {
            return static::$url . $ret[ 'key' ];
        }
    }


    static function fetchOne( string $url )
    {
        $index=strpos($url,"https://static.liaorusanshe.com");
        if($index!==false) return false;

        $client   = new Client([ "verify" => false ]);
        $response = $client->get($url);
        if ($response->getStatusCode() == 200) {
            return static::upload($response->getBody()->getContents());
        }
        else {
            return false;
        }
    }

    /**
     * @param string $url
     * @return array
     * 抓取图片
     */
    static function fetcher( array &$imgUrl ): array
    {

        $client   = new Client([ "verify" => false ]);
        $promises = [];
        foreach ( $imgUrl as &$v ) {
            $index=strpos($v,"https://static.liaorusanshe.com");
            if($index!==false){
                unset($v);
                continue;
            }
            $promises[] = $client->getAsync($v);
        }

        $result = unwrap($promises);
        $upload = function() use ( $result ) {
            foreach ( $result as $key => $v ) {
                if ($v->getStatusCode == 200) {
                    yield static::upload($v->getBody()->getContents());
                }
            }
        };
        $arr    = [];
        foreach ( $upload() as $item ) {
            $arr[] = $item;
        }
        return $arr;
    }

    /**
     * @param string $data
     * @param string $filename
     * @return string base64 上传
     */
    static function uploadToBase64( $data = '', $filename = '' )
    {
        $auth      = new Auth(static::$accessKey, static::$secretKey);
        $token     = $auth->uploadToken(static::$bucket);
        $uploadMgr = new UploadManager();

        if ($filename)
            $filename = static::$picPrefix . $filename;
        else
            $filename = static::$picPrefix . date("Ym") . "/" . substr(md5(time() . mt_rand(10, 99)), 0, 10) . ".png";


        list(
            $ret, $err
            )
            = $uploadMgr->putFile($token, $filename, $data);

        if ($err !== null) {
            return '';
        }
        else {
            return static::$url . $ret[ 'key' ];
        }
    }


    public static function uploadToQiniuBy()
    {

        $stream = file_get_contents($_FILES[ 'file' ][ 'tmp_name' ]);

        //上传图片
        $imgArr = static::upload($stream);

        if ($imgArr) {

            return response()->json([ 'url' => $imgArr ]);
        }
        else {

            return response()->json('error', 500);
        }
    }


}