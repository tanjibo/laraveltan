<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 22/5/2018
 * Time: 3:59 PM
 */

namespace App\Http\OfficialControllers;


use App\Http\Controllers\Controller;
use App\Models\OfficialActivity;

use Illuminate\Http\Response;


class BaseController extends Controller
{
    protected $app      = "";
    protected $activity = '';

    public function __construct()
    {

        $this->app = app("wechat.official_account");

        $this->isActiveActivity();

    }


    public function isActiveActivity()
    {

        $model = OfficialActivity::find(getActivityId());
        if (!$model) {
            $status='活动不存在或已经停止了';
            echo  header("Location:".route("officialAccount.gateway",compact("status")));
            exit;

        }
        $this->activity = $model;
        if (strtotime($model->start_time) > time()) {
            $status='活动还未开始,客官可不要着急呦';
            echo  header("Location:".route("officialAccount.gateway",compact("status")));
            exit;
        }


        if (strtotime($model->end_time) < time()) {
            $status='活动已结束,请关注下一期吧';
            echo  header("Location:".route("officialAccount.gateway",compact("status")));
            exit;
        }

    }



    public function error( array $data = [], int $status = Response::HTTP_INTERNAL_SERVER_ERROR )
    {
        return response()->json($data, $status);
    }

    public function success( array $data = [], int $status = Response::HTTP_OK )
    {
        return response()->json($data, $status);
    }

}