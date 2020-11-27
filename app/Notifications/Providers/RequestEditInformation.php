<?php

namespace App\Notifications\Providers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestEditInformation extends Notification
{
    use Queueable;

    public $data;

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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $aproved_edit = request()->aproved_edit;
        $reject_edit = request()->reject_edit;
        $data = $this->data;

        return (new MailMessage)
                    ->from(getenv('MAIL_FROM_ADDRESS'), getenv('MAIL_FROM_NAME').' - Compras')
                    ->subject("Solicitud de Edición - {$this->data->provider->business_name} - Norson Compras")
                    ->line("El proveedor {$this->data->provider->business_name} ha solicitado modificar su información.")
                    ->line("Motivo: {$this->data->reason}.")
                    ->line("Ingrese al sistema para aprobar/rechazar esta solicitud")
                    ->action('Entrar', $aproved_edit.$this->data->id)
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
            //
        ];
    }
}
