<?php

namespace App\Http\ApiControllers\Front;

use App\Foundation\Lib\WechatTemplate;
use App\Http\ApiControllers\ApiController;
use Illuminate\Http\Request;

class WechatTemplateController extends ApiController
{

    protected  $template;
    public function __construct(WechatTemplate $template) {
       $this->template=$template;
    }

    function getAccessToken(){
        $this->template->accessToken();
    }
    /**
     * @param Request $request
     * 发送支付成功模板通知
     */
   function sendPayTpl(Request $request){

       $prepay_id=explode('=',$request->prepay_id);
       $this->template->sendPayTpl($prepay_id[1],$request->booking_id);
   }
}