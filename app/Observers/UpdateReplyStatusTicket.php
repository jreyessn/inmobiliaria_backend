<?php

namespace App\Observers;

use App\Models\Ticket\TicketMessage;

class UpdateReplyStatusTicket
{
    public function created(TicketMessage $ticketMessage){
        $user = $ticketMessage->user;

        // user support
        if($user->hasPermissionTo("portal admin")){

            if($ticketMessage->ticket->messages->count() == 1){
                $ticketMessage->ticket->update([
                    "reply_status_to_contact" => "Soporte ha abierto el Ticket"
                ]);
            }
            else{
                $ticketMessage->ticket->update([
                    "reply_status_to_contact" => "Soporte ha respondido",
                    "reply_status_to_users" => "Respondido"
                ]); 
            }

        }
        else{

            if($ticketMessage->ticket->messages->count() == 1){
                $ticketMessage->ticket->update([
                    "reply_status_to_users" => "Cliente ha abierto el Ticket"
                ]);
            }
            else{
                $ticketMessage->ticket->update([
                    "reply_status_to_contact" => "Has respondido",
                    "reply_status_to_users" => "Cliente ha respondido"
                ]); 
            }
        }
    }

  
}
