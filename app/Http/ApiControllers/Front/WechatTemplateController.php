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
       
       $this->template->sendPayTpl($request->prepay_id,$request->booking_id);
   }
}
