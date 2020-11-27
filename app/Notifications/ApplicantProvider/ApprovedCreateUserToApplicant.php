<?php

namespace App\Notifications\ApplicantProvider;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ApprovedCreateUserToApplicant extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tradename)
    {
        $this->tradename = $tradename;
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
                    ->line(new HtmlString('Su solicitud del proveedor por nombre comercial <strong>' . $this->tradename . '</strong> ha sido aceptada. Ya se contactó al correo del proveedor para que pueda dar de alta su información en el sistema.'))
                    ->salutation('Un saludo.');
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
