<?php

function userHasAccess(Array $permission){
    if(!auth()->user()->hasAnyPermission($permission)){

        flash()->overlay('您没有对应的权限!如果需要对应的权限,请找管理员开启','这就悲剧了');

        abort(403,'没有权限');
    }
}

function userHasRole($role){
    if(!auth()->user()->hasRole($role)){

        flash()->overlay('您没有对应的权限!如果需要对应的权限,请找管理员开启','这就悲剧了');

        abort(403,'没有权限');
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