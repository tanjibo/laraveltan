<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 12/9/2018
 * Time: 3:01 PM
 */

namespace App\Http\ApiControllers\Experience;


use App\Http\ApiControllers\ApiController;
use App\Http\Resources\Experience\MiniSetttingResource;
use App\Models\MiniCommonSettings;

class MiniSettingsController extends ApiController
{

     public function index(){

         return $this->success(new MiniSetttingResource(MiniCommonSettings::experienceMiniSetting()));
     }
}