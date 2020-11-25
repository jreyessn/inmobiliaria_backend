<?php

namespace App\Notifications\ApplicantProvider;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovedCreateUser extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($params = array())
    {
        $this->params = $params;
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
                ->subject("Proceso de Alta de Proveedor - Norson Alimentos")
                ->greeting("Buen día.")
                ->line('Se envía enlace para iniciar con el Proceso de Alta de Proveedor de Norson. Deberá acceder al siguiente enlace para formalizar su registro de documentos')
                ->line("Usuario: {$this->params['email']}")
                ->line("Contraseña: {$this->params['password']}")
                ->action('Entrar', getenv('APP_FRONTEND'))
                ->line('¡Gracias por querer ser parte de nosotros!');
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
