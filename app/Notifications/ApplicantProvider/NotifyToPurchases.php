<?php

namespace App\Notifications\ApplicantProvider;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotifyToPurchases extends Notification
{
    use Queueable;

    private $applicant;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($applicant)
    {
        $this->applicant = $applicant;
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
                    ->subject("Nueva Solicitud para Alta de Proveedor - Norson Compras")
                    ->line(new HtmlString('Se ha registrado una nueva solicitud de proveedor por parte del solicitante: <strong>' . $this->applicant . '</strong>'))
                    ->action('Entrar', getenv('APP_FRONTEND'))
                    ->salutation('-');
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
