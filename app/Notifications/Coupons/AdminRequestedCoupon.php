<?php

namespace App\Notifications\Coupons;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class AdminRequestedCoupon extends Notification
{
    use Queueable;

    private $data = [];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
                    ->subject("Solicitud en Proceso #{$this->data["folio"]} - " . getenv("APP_NAME"))
                    ->line(new HtmlString("Se ha procesado una nueva solicitud de cupones:"))
                    ->line(new HtmlString("- <strong>Folio:</strong> {$this->data['folio']}"))
                    ->line(new HtmlString("- <strong>Nombre Comercial:</strong> {$this->data['tradename']}"))
                    ->line(new HtmlString("- <strong>Cantidad:</strong> {$this->data['quantity']}"))
                    ->line(new HtmlString("- <strong>Solicitante:</strong> {$this->data['username']}"))
                    ->line(new HtmlString("Puede verificar la lista de solicitudes mediante el siguiente enlace:"))
                    ->action("Entrar", getenv("APP_FRONTEND") . "/coupons-request/")
                    ->salutation("-");

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
