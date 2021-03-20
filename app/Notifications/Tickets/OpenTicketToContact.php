<?php

namespace App\Notifications\Tickets;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class OpenTicketToContact extends Notification
{
    use Queueable;


    private $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $data)
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
        $channels = ['database', 'mail'];

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
            ->subject("Ticket Abierto [#{$this->data['id']}]")
            ->line(new HtmlString("<strong>{$this->data["name"]}</strong> ha abierto un nuevo ticket y se lo ha asignado. Asunto <strong>{$this->data['title']} [#{$this->data['id']}]</strong>."))
            ->line(new HtmlString("Ingrese al siguiente enlace para dar un seguimiento directo."))
            ->action('Entrar', getenv("APP_FRONTEND")."guest/ticket/{$this->data['id_encrypted']}");

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
            "name" => $this->data["name"],
            "title" => $this->data["title"],
            "subject" => "Ticket Abierto [#{$this->data['id']}]",
            "message" => "<strong>{$this->data["name"]}</strong> ha abierto un nuevo ticket y se lo ha asignado. Asunto <strong>{$this->data['title']} [#{$this->data['id']}]</strong>.",
            "id" => $this->data["id"],
            "url" => "tickets/{$this->data['id']}"
        ];
    }

    /**
     * Send One Signal notification
     */
    public function toOneSignal($notifiable)
    {

        $url = getenv("APP_FRONTEND")."guest/ticket/{$this->data['id_encrypted']}";

        return OneSignalMessage::create()
            ->setSubject("Ticket Abierto [#{$this->data['id']}]")
            ->setBody("{$this->data["name"]} ha abierto un nuevo ticket y se lo ha asignado")
            ->setUrl($url)
            ->setIcon(public_path('logo.png'));

    }
}
