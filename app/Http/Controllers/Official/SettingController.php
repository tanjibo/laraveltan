<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 30/5/2018
 * Time: 12:09 PM
 */

namespace App\Http\Controllers\Official;


use App\Http\Controllers\Controller;
use App\Models\OfficialAccountDefaultSetting;
use Illuminate\Http\Request;
use Repositories\OfficialAccountMenuRepository;

class SettingController extends Controller
{


    function index()
    {
       $model= OfficialAccountDefaultSetting::officialAccountHasDefaultSetting();
        return view('official.setting.create_and_edit',compact('model'));
    }

    /**
     * @param OfficialAccountMenuRepository $menu
     * 重置公众栏菜单
     */
    function resetOfficialAccountMenu(OfficialAccountMenuRepository $menu){
       return response()->json($menu->reset());
    }

    public function store( Request $request )
    {
        OfficialAccountDefaultSetting::query()->truncate();
      return response()->json(OfficialAccountDefaultSetting::query()->create($request->all()));

    }
}