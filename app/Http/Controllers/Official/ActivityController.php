<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 22/5/2018
 * Time: 4:38 PM
 */

namespace App\Http\Controllers\Official;

use App\Http\Controllers\Controller;
use App\Models\OfficialAccountDefaultSetting;
use App\Models\OfficialActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ActivityController extends Controller
{

    public function index()
    {
        echo phpinfo();exit;
        if (!OfficialAccountDefaultSetting::officialAccountHasDefaultSetting()) {

            return redirect()->route('official_account_setting.index');

        }

        $model = OfficialActivity::list();
        return view('official.activity.index', compact('model'));
    }


    public function create()
    {
        return view('official.activity.create_and_edit');
    }

    public function store( Request $request )
    {
        $model = OfficialActivity::addActivity();
        return response()->json($model);
    }

    public function edit( Request $request, OfficialActivity $official_activity )
    {
        $model = $official_activity;
        $share = $official_activity->share_settings()->first();
        return view('official.activity.create_and_edit', compact('model', 'share'));
    }


    /**
     * @param Request $request
     * @param OfficialActivity $official_activity
     * @return \Illuminate\Http\JsonResponse
     * 更新
     */
    public function update( Request $request, OfficialActivity $official_activity )
    {
        $official_activity->fill($request->all())->save();
          $official_activity->share_settings()->delete();
          $model= $official_activity->share_settings()->create($request->share);
        return response()->json($model);
    }

    public function destroy( Request $request, OfficialActivity $official_activity )
    {
        return response()->json($official_activity->delete());
    }

    /**
     * @param Request $request
     * @return mixed
     * 下载二维码
     */
    public function download( Request $request )
    {
        $disk = Storage::disk('qiniu');
        $url  = str_replace("https://static.liaorusanshe.com", '', $request->url);
        return $disk->download($url);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 本地上传
     */
    public function upload( Request $request )
    {
        $path = '/storage/' . $request->file('file')->store('official_activity_share', 'public');

        return response()->json([ 'url' => $path ]);
    }

    /**
     * 启动活动
     */
    public function launch( Request $request )
    {

        $official_activity = OfficialActivity::query()->findOrFail($request->official_activity_id);

        //代表开启活动
        if (!$request->close) {
            OfficialActivity::query()->where('id', "!=", $official_activity->id)->update([ 'is_active' => 2 ]);

        }
        //重新生成菜单栏
        makeMenu($official_activity);

        $official_activity->is_active = $request->close == 'close' ? 2 : 1;

        return response()->json($official_activity->save());
    }

}

