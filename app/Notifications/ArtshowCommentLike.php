<?php

namespace App\Notifications;

use App\Models\ArtShowComment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ArtshowCommentLike extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $artShowComment;
    public function __construct(ArtShowComment $artShowComment)
    {
        $this->artShowComment=$artShowComment;
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

    public function toDatabase($notifiable)
    {


        return [
            'user_avatar'    => auth()->user()->avatar,
            'user_name'      => auth()->user()->nickname,
            'art_show'       => $this->artShowComment->art_show->name,
            'art_show_cover' => $this->artShowComment->art_show->cover,
            'art_show_id'    => $this->artShowComment->art_show->id,
            'art_show_url'   => route('art.show', $this->artShowComment->art_show->id),
            'id'          => $this->artShowComment->id,
            'comment'          => $this->artShowComment->comment,
            'type'           => 'comment_like'
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
