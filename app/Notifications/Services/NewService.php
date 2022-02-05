<?php

namespace App\Notifications\Services;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class NewService extends Notification
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
                    ->subject("Se ha agendado un nuevo servicio - " . getenv("APP_NAME"))
                    ->line(new HtmlString("El sistema ha agendado un servicio de {$this->data['service_category']} para el día {$this->data['event_date']}:"))
                    ->line(new HtmlString("- <strong>Equipo:</strong> {$this->data['equipment_name']}"))
                    ->line(new HtmlString("- <strong>Pieza:</strong> {$this->data['equipment_part_name']}"))
                    ->line(new HtmlString("Ingrese al sistema para asignar el técnico que se encargará de atenderlo"))
                    ->action("Entrar", getenv("APP_FRONTEND") . "/services/edit/{$this->data['id']}")
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
        $body = "Debe asignar el técnico encargado. Fecha: {$this->data['event_date']}. Equipo: {$this->data['equipment_name']}";
        $url  = getenv("APP_FRONTEND") . "/services/edit/{$this->data['id']}";

        if($this->data["equipment_part_name"] != "No aplica"){
            $body .= ", {$this->data["equipment_part_name"]}.";
        }

        return OneSignalMessage::create()
            ->setSubject("Se ha agendado un nuevo servicio - Mayocabo")
            ->setBody($body)
            ->setUrl($url)
            ->setIcon(public_path('logo.png'));

    }
}
