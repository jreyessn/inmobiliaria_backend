<?php

namespace App\Notifications\Customers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SendLinkProfile extends Notification
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
                    ->subject("Enlace de Solicitud de Cupones - " . getenv("APP_NAME"))
                    ->line('Estimado cliente,')
                    ->line(new HtmlString("Se le compartirá el siguiente enlace donde podrá realizar solicitudes de cupones y ver información general sobre su balance."))
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
