<?php

namespace App\Listeners;

use App\Events\ArtShowNotificationEvent;
use App\Foundation\Lib\ArtShowWechatNotify;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArtShowNotificationEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $request;
    public $notify;
    public function __construct(Request $request,ArtShowWechatNotify $notify)
    {
        file_put_contents('./2.txt',$request->all());
        $this->request=$request;
        $this->notify=$notify;
    }

    /**
     * Handle the event.
     * 发送微信小程序同时
     * @param  ArtShowNotificationEvent  $event
     * @return void
     */
    public function handle(ArtShowNotificationEvent $event)
    {

        $data=[
             'open_id'=>auth()->user()->art_open_id,
//           'open_id'=>$event->comment->replies_to_user->owner->art_open_id,
//            'art_open_id'=>'oKsQH0ftd9h1aDzDuRf4PkPpUiSE',
            'form_id'=>$this->request->form_id,
            'reply_user'=>$event->user->nickname,
            'parent_comment_id'=>$event->comment->parent_id,
            'reply_comment'=>$event->comment->comment,
            'art_show_name'=>$event->comment->art_show->name,
            'date'=>$event->comment->created_at->toDateTimeString()
        ];

        $this->notify->commentReply($data);
    }
}
