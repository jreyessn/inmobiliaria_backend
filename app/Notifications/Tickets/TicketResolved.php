<?php

namespace App\Notifications\Tickets;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use NotificationChannels\OneSignal\OneSignalMessage;

class TicketResolved extends Notification
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
        $url = getenv("APP_FRONTEND")."tickets/{$this->data['id']}";

        return (new MailMessage)
                    ->subject("Ticket Resuelto - {$this->data['title']} [#{$this->data['id']}]")
                    ->line(new HtmlString("El ticket ha sido marcado como resuelto por <strong>{$this->data['name']}</strong>."))
                    ->action('Entrar', $url)
                    ->salutation('-');
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
            "subject" => "Ticket Resuelto - <strong>{$this->data['title']} [#{$this->data['id']}]</strong>",
            "message" => "El ticket ha sido marcado como resuelto por {$this->data['name']}.",
            "url" => "tickets/{$this->data['id']}"
        ];
    }

    /**
     * Send One Signal notification
     */
    public function toOneSignal($notifiable)
    {

        $url = getenv("APP_FRONTEND")."tickets/{$this->data['id']}";
        
        return OneSignalMessage::create()
            ->setSubject("Ticket Resuelto - {$this->data['title']} [#{$this->data['id']}]")
            ->setBody("El ticket ha sido marcado como resuelto por {$this->data['name']}.")
            ->setUrl($url)
            ->setIcon(public_path('logo.png'));

    }
}
