<?php

namespace App\Notifications\Services;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class AssignedService extends Notification
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
                    ->subject("Se le ha asignado un servicio para atender - " . getenv("APP_NAME"))
                    ->line(new HtmlString("El servicio de {$this->data['service_category']} está agendado para el día {$this->data['event_date']}:"))
                    ->line(new HtmlString("- <strong>Equipo:</strong> {$this->data['equipment_name']}"))
                    ->line(new HtmlString("- <strong>Pieza:</strong> {$this->data['equipment_part_name']}"))
                    ->line(new HtmlString("- <strong>Tipo de Servicio:</strong> {$this->data['type_service_name']}"))
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
        $body = "Agendado para la fecha {$this->data['event_date']}. Equipo: {$this->data['equipment_name']}";
        $url  = getenv("APP_FRONTEND")."services/complete/{$this->data['id']}";

        if($this->data["equipment_part_name"] != "No aplica"){
            $body .= ", {$this->data["equipment_part_name"]}";
        }

        return OneSignalMessage::create()
            ->setSubject("Nuevo servicio pendiente - Mayocabo")
            ->setBody($body)
            ->setUrl($url)
            ->setIcon(public_path('logo.png'));

    }
}
