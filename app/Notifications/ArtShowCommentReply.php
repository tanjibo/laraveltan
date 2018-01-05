<?php

namespace App\Notifications;

use App\Models\ArtShowComment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ArtShowCommentReply extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $reply;


    public function __construct(ArtShowComment $reply)
    {
        $this->reply=$reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable){


        return [
           'user_avatar'=>auth()->user()->avatar,
           'user_name'=>auth()->user()->nickname,
           'art_show'=>$this->reply->art_show->name,
           'art_show_cover'=>$this->reply->art_show->cover,
           'art_show_id'=>$this->reply->art_show->id,
           'art_show_url'=>route('art.show',$this->reply->art_show->id),
           'reply'=>$this->reply->comment,
           'type'=>'reply',
           'comment_id'=>$this->reply->id,
           'parent_comment_id'=>$this->reply->parent_id
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
