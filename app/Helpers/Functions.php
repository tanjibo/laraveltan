<?php


function toHttps( $url, $urlPrams = true )
{
    if (!\Illuminate\Support\Str::contains($url, [ '?imageMogr2', '?imageView2' ])) {
        if ($urlPrams)
            $url = $url . '?imageMogr2/auto-orient/strip/format/webp/size-limit/$(fsize)!/interlace/1';
    }

    preg_match('/^(http[s]?)\:\/\/(.+)/i', $url, $data);
    if ($data[ 1 ] && $data[ 1 ] == 'http')
        return str_replace('http', 'https', $url);
    else
        return $url;

}

/**
 * @param string $route
 * @param $activity
 * @return string
 * 生成混淆地址
 */
function makeActivityMixUrl( string $route, $activity )
{
    return route($route, [ 'app_source' => \Qiniu\base64_urlSafeEncode($activity->id), 'mix' => str_random(32) ]);
}


function getActivityId()
{
    $id = \Qiniu\base64_urlSafeDecode(request()->app_source) ?: request()->official_activity_id;
    return $id;
}

/**
 * @param $activity
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 * 生成微信菜单
 */
function makeMenu( $activity )
{
    app(\Repositories\OfficialAccountMenuRepository::class)->makeMenu($activity);
}

/**
 * @param string $url
 * @return array
 * 生成二维码，并上传到七牛
 */
function QrCode( string $url )
{
    $qrCode   = new \Endroid\QrCode\QrCode($url);
    $response = new \Endroid\QrCode\Response\QrCodeResponse($qrCode);
    return \App\Foundation\Lib\Qiniu::upload($response->getContent());
}

function filterSentenceWord( string $word )
{
    $interference = [ '&', '*' ];
    $filename     = './sensitiveWord.txt'; //每个敏感词独占一行
    \Yankewei\LaravelSensitive\Facades\Sensitive::interference($interference); //添加干扰因子
    \Yankewei\LaravelSensitive\Facades\Sensitive::addwords($filename); //需要过滤的敏感词
    $words = \Yankewei\LaravelSensitive\Facades\Sensitive::filter($word);
    return $words;
}

/**
 * @return array
 * 获取laravel-wechat 用户信息
 */
function wechatOauthUser(): array
{

    $user = session('wechat.oauth_user.default');
    return $user->original;
}

function userHasAccess( Array $permission )
{
    if (!auth()->user()->hasAnyPermission($permission)) {

        flash()->overlay('您没有对应的权限!如果需要对应的权限,请找管理员开启', '这就悲剧了');

        abort(403, '没有权限');
    }
}

function userHasRole( $role )
{
    if (!auth()->user()->hasRole($role)) {

        flash()->overlay('您没有对应的权限!如果需要对应的权限,请找管理员开启', '这就悲剧了');

        abort(403, '没有权限');
    }
}

/*
 *
 * 获取两个日期间的日期
 * @param $start [开始时间]
 * @param $end [结束时间]
 * @return array
 */
function splitDate( $start, $end )
{
    $ds    = [];
    $start = strtotime($start);
    $end   = strtotime($end);

    while ( $start < $end ) {
        $ds[]  = date('Y-m-d', $start);
        $start = strtotime('+1 day', $start);
    }

    return $ds;
}

/**
 * @param $start
 * @param $end
 * @return array
 */
function splitDateMore( $start, $end )
{
    $ds = splitDate($start, $end);

    array_push($ds, date("Y-m-d", strtotime($end)));
    return $ds;
}

/**
 * @param string $data
 * @return string
 * 头像
 */
function icon( $data = '' )
{
    $icon = new \Identicon\Identicon();
    // $icon->displayImage('foo', 64, array(rand(0,255), rand(0,255), rand(0,255)));
    return $icon->getImageDataUri($data ?: str_random());
}

/**
 * @return \Illuminate\Database\Eloquent\Collection|static[]
 * 获取自己员工
 */
function homeUser()
{
    $admin_user_id
        = [
        1,
        5,
        9,
        14,
        54,
        4,
        170,
        97,
        175,
        166,
        308,
        126,
        205,
        105,
        176,
        177,
        168,
        111,
        53,
    ];
    return \App\Models\User::query()->whereIn('id', $admin_user_id)->select('id', 'nickname', 'avatar')->get();

}