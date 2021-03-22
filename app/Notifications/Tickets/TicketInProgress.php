<?php

namespace App\Notifications\Tickets;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class TicketInProgress extends Notification
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
                    ->subject("Ticket en Progreso - {$this->data['title']} [#{$this->data['id']}]")
                    ->greeting("Buen día.")
                    ->line(new HtmlString("Su ticket se encuentra ahora en estado de proceso."))
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
            "subject" => "Ticket en Progreso - <strong>{$this->data['title']} [#{$this->data['id']}]</strong>",
            "message" => "Su ticket se encuentra ahora en estado de proceso.",
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
            ->setSubject("Nuevo Mensaje - {$this->data['title']} [#{$this->data['id']}]")
            ->setBody("Su ticket se encuentra ahora en estado de proceso.")
            ->setUrl($url)
            ->setIcon(public_path('logo.png'));

    }
}
