<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailConfirmation extends Notification
{
    use Queueable;

    /**
     * The Email confirmation token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     * Email confirmation constructor.
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        return (new MailMessage)
                    ->line('Hai ricevuto questa email perché sei stato appena registrato sulla piattaforma '. config('app.name'))
                    ->line('Clicca sul pulsante \'Conferma email\' per confermare la tua email e completare la registrazione')
                    ->action('Conferma email', url(config('app.url').route('confirmation.email', $this->token, false)))
                    ->line('Se non sei stato tu a registrarti, non intraprendere nessuna azione, la registrazione sarà cancellata automaticamente');
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
