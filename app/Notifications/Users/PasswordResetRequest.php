<?php

namespace App\Notifications\Users;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Http\Request;

class PasswordResetRequest extends Notification implements ShouldQueue
{
    use Queueable;
    protected $token;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $token )
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

        $url = request()->url .$this->token;

        return (new MailMessage)
            ->subject('Recuperar contraseña')
            ->greeting('Hola, '.$notifiable->first_name.' '.$notifiable->last_name)
            ->line('Estás recibiendo este email porque se ha solicitado un cambio de contraseña para tu cuenta.')
            ->action('Recuperar contraseña', $url)
            ->line('Si no has solicitado un cambio de contraseña, puedes ignorar o eliminar este e-mail.')
            ->salutation('Saludos,')
            ->salutation(env('APP_NAME'));

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
