<?php

namespace App\Notifications\Tickets;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\SlackWebhookChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;

class NewReplyTicket extends Notification
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

        $channels = ['database'];

        if($notifiable->hasPermissionTo('portal admin')){
            array_push($channels, 'mail');
        }

        if($notifiable->players->count() > 0){
            array_push($channels, OneSignalChannel::class);
        }

        if($notifiable->slack_player){
            array_push($channels, SlackWebhookChannel::class);
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
                    ->subject("Nuevo Mensaje - {$this->data['title']} [#{$this->data['id']}]")
                    ->line(new HtmlString("<strong>{$this->data["name"]}</strong> ha respondido el ticket"))
                    ->line(new HtmlString($this->data["message"]))
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
            "name" => $this->data["name"],
            "title" => $this->data["title"],
            "subject" => "Nuevo Mensaje <strong>{$this->data['title']} [#{$this->data['id']}]</strong>",
            "message" => "<strong>{$this->data["name"]}</strong> ha respondido el ticket.",
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
            ->content("<{$url}|Ticket [#{$this->data['id']}] - {$this->data['title']}>. *{$this->data["name"]}* ha respondido el ticket.")
            ->from("Soporte JeanLogistics", ":mega:")
            ->to($notifiable->slack_player)
            ->http(['http_errors' => false]);
            
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
            ->setBody("{$this->data["name"]} ha respondido el ticket")
            ->setUrl($url)
            ->setIcon(public_path('logo.png'));

    }
}
