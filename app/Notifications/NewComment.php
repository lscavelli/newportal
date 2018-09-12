<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewComment extends Notification
{
    use Queueable;

    private $message;
    private $comment;

    /**
     * Create a new notification instance.
     * Newcomment constructor.
     */
    public function __construct($msg, $comment)
    {
        $this->message = $msg;
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $service = strtolower(array_get(config('newportal.services'),$this->comment->commentable_type));
        $service_id = $this->comment->commentable_id;
        $id = $this->comment->id;
        $url = url('admin/comments/'.$service.'/'.$service_id.'/edit/'.$id);
        return (new MailMessage)
                    ->line($this->message)
                    ->action('Accedi al Commento', $url)
                    ->line('grazie per l\'attenzione!');
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
