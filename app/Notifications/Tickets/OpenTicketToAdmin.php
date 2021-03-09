<?php

namespace App\Notifications\Tickets;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class OpenTicketToAdmin extends Notification
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
                    ->line(new HtmlString("<strong>{$this->data["name"]}</strong> ha abierto un ticket con el asunto <strong>{$this->data['title']}</strong>"))
                    ->line("Por favor, ingresa al sistema para dar seguimiento.")
                    ->action('Entrar', getenv("APP_FRONTEND")."tickets/{$this->data['id']}")
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
            "name" => $this->data["name"],
            "title" => $this->data["title"],
            "id" => $this->data["id"],
            "url" => getenv("APP_FRONTEND")."tickets/{$this->data['id']}"
        ];
    }
}
