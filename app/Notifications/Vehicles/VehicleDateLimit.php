<?php

namespace App\Notifications\Vehicles;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class VehicleDateLimit extends Notification
{
    use Queueable;

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
        $channels = ["mail"];

        if($notifiable->players->count() > 0){
            array_push($channels, OneSignalChannel::class);
        }

        return $channels;
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
                    ->subject("Fecha Límite de Mantenimiento alcanzada para una Unidad - " . getenv("APP_NAME"))
                    ->line(new HtmlString("Una de las unidades del sistema ha alcanzado la fecha limite de mantenimiento:"))
                    ->line(new HtmlString("- <strong>Unidad:</strong> {$this->data['vehicle_label']}"))
                    ->line(new HtmlString("- <strong>Fecha Límite:</strong> {$this->data['expired_at']}"))
                    ->action("Verificar", getenv("APP_FRONTEND") . "/units/edit/{$this->data['id']}")
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

    /**
     * Send One Signal notification
     */
    public function toOneSignal($notifiable)
    {
        $body = "Fecha: {$this->data['expired_at']}. Unidad: {$this->data['vehicle_label']}";
        $url  = getenv("APP_FRONTEND") . "/units/edit/{$this->data['id']}";

        return OneSignalMessage::create()
            ->setSubject("Fecha Límite de Mantenimiento alcanzada para una Unidad - Mayocabo")
            ->setBody($body)
            ->setUrl($url)
            ->setIcon(public_path('logo.png'));

    }
}
