<?php

namespace App\Notifications\Vehicles;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class LicenseExpired extends Notification
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
                    ->subject("Licencia de Unidad Expirada - " . getenv("APP_NAME"))
                    ->line(new HtmlString("- <strong>Unidad:</strong> {$this->data['vehicle_label']}"))
                    ->line(new HtmlString("- <strong>Chofer:</strong> {$this->data['name']}"))
                    ->line(new HtmlString("- <strong>Fecha de Vencimiento:</strong> {$this->data['expired_at']}"))
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
        $body = "Fecha: {$this->data['expired_at']}. Unidad: {$this->data['vehicle_label']}. Chofer: {$this->data['name']}}";

        return OneSignalMessage::create()
            ->setSubject("Licencia de Unidad Expirada - Mayocabo")
            ->setBody($body)
            ->setIcon(public_path('logo.png'));
    }
}
