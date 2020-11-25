<?php

namespace App\Notifications\ApplicantProvider;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RejectCreateUser extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($reason = '')
    {
        $this->reason = $reason;
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
                ->line('Lamentamos informar que su solicitud para iniciar el proceso de registro para ser proveedor de norson ha sido rechazado.')
                ->line("Motivo: {$this->reason}")
                ->line("Por favor, si tienes dudas sobre el caso y desea más información sobre el proceso de registro, le recomendamos contactarnos al siguiente correo: example@example.com");
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
