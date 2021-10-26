<?php

namespace App\Notifications\Coupons;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class CustomerRequestedCoupon extends Notification
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
                    ->line('Estimado cliente,')
                    ->line(new HtmlString("Se ha procesado una nueva solicitud de cupones por una cantidad de <strong>{$this->data["quantity"]}</strong>"))
                    ->line(new HtmlString("Recuerde que puede revisar su balance mediante el siguiente enlace:"))
                    ->action("Entrar", getenv("APP_FRONTEND") . "/profile/" . $this->data["encrypt_id"])
                    ->salutation(new HtmlString("Se despide, <br /> " . getenv("APP_NAME") ."."));
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
