<?php

namespace App\Http\ApiControllers\Tearoom;


use App\Http\ApiControllers\ApiController;
use App\Models\Api\TearoomBooking;
use EasyWeChat\Factory;

class WechatTemplateController extends ApiController
{

     protected  $mini;

    public function __construct() {
        $this->mini = Factory::miniProgram(config('wechat.mini_program.tearoom'));
    }


    function sendPaySuccessNotify(TearoomBooking $booking,$formId){

        $this->mini->template_message->send(
            [
                'touser'      => $booking->user->tearoom_open_id,
                'template_id' => 'yzVKNh-DIT1YONhWy1Yl1nGBPDc6WoOV5lrunhYfWWM',
                'page'        => '/pages/orderlist?orderStatus=1',
                'form_id'     => $formId,
                'data'        => [
                    'keyword1' => $booking->real_fee.' 元',
                    'keyword2' => $booking->customer,
                    'keyword3' => $booking->tearoom->name,
                    'keyword4' => $booking->date.' '.$booking->time,
                    'keyword5' =>"茶舍地址：北京市东城区安定门西大街9号",
                    'keyword6' => "13051747151",
                    'keyword7' =>$booking->mobile,
                    'keyword8' => 'PS:请关注小程序关联公众号"了如三舍"以便收到最新资讯😊',
                ],
            ]
        );
        return $this->success(['data'=>'success']);
    }

    function sendCancelNotify(TearoomBooking $booking,$form_id){

        $this->mini->template_message->send(
            [
                'touser'      => $booking->user->tearoom_open_id,
                'template_id' => '7qEVrNOLoSxgA5H_8hJuPN2zFI10iBNewOZK29FEnoI',
                'page'        => '/pages/orderlist?orderStatus=-10',
                'form_id'     =>$form_id,
                'data'        => [
                    'keyword1' => "订单已成功申请'.$booking->real_fee.'元退款，请关注您的账户变动",
                    'keyword2' => date('Y-m-d,H:i:s'),
                    'keyword3' => "预计到账时间为5个工作日",
                ],
            ]
        );
        return $this->success(['data'=>'success']);
    }

}
