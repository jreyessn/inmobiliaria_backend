<?php

namespace App\Notifications\Tickets;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class TicketClosed extends Notification
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
        if($notifiable->hasPermissionTo("portal customer") ?? false){
            $url = getenv("APP_FRONTEND")."guest/ticket/{$this->data['id_encrypted']}";
        }
        else{
            $url = getenv("APP_FRONTEND")."tickets/{$this->data['id']}";
        }

        return (new MailMessage)
                    ->subject("Ticket {$this->data['status_text']} - {$this->data['title']} [#{$this->data['id']}]")
                    ->line(new HtmlString("<strong>{$this->data['name']}</strong> ha dado por {$this->data['status_text']} el Ticket."))
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
            "subject" => "Ticket {$this->data['status_text']} - <strong>{$this->data['title']} [#{$this->data['id']}]</strong>",
            "message" => "<strong>{$this->data['name']}</strong> ha dado por {$this->data['status_text']} el Ticket.",
            "url" => "tickets/{$this->data['id']}"
        ];
    }

    /**
     * Send One Signal notification
     */
    public function toOneSignal($notifiable)
    {

        if($notifiable->hasPermissionTo("portal customer") ?? false){
            $url = getenv("APP_FRONTEND")."guest/ticket/{$this->data['id_encrypted']}";
        }
        else{
            $url = getenv("APP_FRONTEND")."tickets/{$this->data['id']}";
        }

        return OneSignalMessage::create()
            ->setSubject("Ticket {$this->data['status_text']} - {$this->data['title']} [#{$this->data['id']}]")
            ->setBody("{$this->data['name']} ha dado por {$this->data['status_text']} el Ticket.")
            ->setUrl($url)
            ->setIcon(public_path('logo.png'));

    }
}
