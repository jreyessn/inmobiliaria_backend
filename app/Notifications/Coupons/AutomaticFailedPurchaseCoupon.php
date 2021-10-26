<?php

namespace App\Notifications\Coupons;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class AutomaticFailedPurchaseCoupon extends Notification
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
                    ->subject("[Suscripción] Compra de Cupones Fallida - " . getenv("APP_NAME"))
                    ->line(new HtmlString("Ha ocurrido un error al realizar la compra de los cupones del cliente {$this->data['tradename']}. Notificar a sistemas de este problema."))
                    ->line(new HtmlString("Resumen de Suscripción:"))
                    ->line(new HtmlString("- <strong>Cantidad:</strong> {$this->data["quantity"]}"));
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
