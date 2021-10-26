<?php

namespace App\Notifications\Coupons;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class AutomaticPurchaseCoupon extends Notification
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

        $next_pay_date = $this->data["next_pay_date"]->format("d/m/Y"); 

        return (new MailMessage)
                    ->subject("[Suscripción] Compra de Cupones #{$this->data["folio"]} - " . getenv("APP_NAME"))
                    ->line('Estimado cliente,')
                    ->line(new HtmlString("Se ha registrado una compra de cupones hoy según los críterios de su suscripción. La próxima fecha de compra está pautada para el {$next_pay_date}"))
                    ->line(new HtmlString("Resumen:"))
                    ->line(new HtmlString("- <strong>Cantidad adquirida:</strong> {$this->data["quantity"]}"))
                    ->line(new HtmlString("- <strong>Costo:</strong> ".currency()." {$this->data["total"]}"))
                    ->line(new HtmlString("- <strong>Cantidad disponible:</strong> {$this->data["quantity_total"]}"))
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
