<?php

namespace App\Notifications\Tickets;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class OpenTicketToAssigned extends Notification
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
            ->line(new HtmlString("<strong>{$this->data["name"]}</strong> le ha asignado para dar seguimiento al ticket con asunto <strong>{$this->data['title']} [#{$this->data['id']}]</strong>."))
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
            "subject" => "Ticket Abierto [#{$this->data['id']}]",
            "message" => "<strong>{$this->data["name"]}</strong> le ha asignado para dar seguimiento al ticket con asunto <strong>{$this->data['title']} [#{$this->data['id']}]</strong>.",
            "id" => $this->data["id"],
            "url" => "tickets/{$this->data['id']}"
        ];
    }

    /**
     * Get the slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toSlack($notifiable)
    {
        $url = getenv("APP_FRONTEND")."tickets/{$this->data['id']}";

        return (new SlackMessage)
            ->info()
            ->content("<{$url}|Ticket Abierto [#{$this->data['id']}] - {$this->data['title']}>. Se le ha asignado para dar seguimiento.")
            ->from("Soporte JeanLogistics", ":ghost:")
            ->to('#general');
            
    }

    /**
     * Send One Signal notification
     */
    public function toOneSignal($notifiable)
    {
        $url = getenv("APP_FRONTEND")."tickets/{$this->data['id']}";

        return OneSignalMessage::create()
            ->setSubject("Ticket Abierto [#{$this->data['id']}]")
            ->setBody("{$this->data["name"]} le ha asignado para dar seguimiento al ticket")
            ->setUrl($url)
            ->setIcon(public_path('logo.png'));

    }
}
