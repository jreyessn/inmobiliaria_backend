<?php

namespace App\Notifications\Providers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Contracted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return (new MailMessage)
            ->subject("Ha sido dado de Alta como Proveedor - Norson Alimentos")
            ->greeting("Buen día.")
            ->line("Por este medio, aprovechamos para enviarle un saludo y para notificarle que ya se encuentra dado de alta como proveedor de Norson.")
            ->line("El número de proveedor que se le asignó es el 0, el cual aparecerá en todas las órdenes de compra que sean emitidas a su nombre.")
            ->line("Le confirmamos que ese mismo número será su usuario para ingresar al Portal de proveedores de Norson, en este portal usted registrará sus 
                    facturas para que puedan ser programadas para pago. 
                    La contraseña inicial para ingresar es su RFC,  el sistema solicitará el cambio de contraseña la primera vez que 
                    ingrese y en ese momento podrá especificar una nueva contraseña si así lo desea. Solo tendrá que repetir la contraseña 
                    deseada para la verificación de la correcta escritura y deberá presionar el botón “Cambiar Clave”.")
            ->line("Favor de leer todos los documentos para Proveedores de Norson:")
            ->line("- Manual para Uso del Portal de Proveedores")
            ->line("- Política de Proveedores de Norson")
            ->line("- Guia de Consideraciones medioambientales para Proveedores de Norson")
            ->line("- Reglamento de ingreso de contratistas y proveedores")
            ->line("- POE 17 Visitantes, Proveedores y contratistas")
            ->line("- Comunicado Norson CDF")
            ->line('¡Gracias por querer ser parte de nosotros!');
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
