<?php

namespace App\Notifications\Tickets;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

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
        return ['mail', 'database'];
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
            ->action('Entrar', getenv("APP_FRONTEND")."support/{$this->data['id_encrypted']}");

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
            "id" => $this->data["id"],
            "url" => getenv("APP_FRONTEND")."tickets/{$this->data['id']}"
        ];
    }
}
