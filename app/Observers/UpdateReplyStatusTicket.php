<?php

namespace App\Observers;

use App\Models\Ticket\TicketMessage;
use App\Models\Ticket\TicketTimeline;

class UpdateReplyStatusTicket
{
    public function created(TicketMessage $ticketMessage){
        $user = $ticketMessage->user;

        // user support
        if($user && $user->hasPermissionTo("portal admin")){

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

                TicketTimeline::create([
                    'made_by_user' => $user->id,
                    'ticket_id' => $ticketMessage->ticket->id,
                    'note' => "Ha respondido."
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

                TicketTimeline::create([
                    'made_by_user' => $user->id,
                    'ticket_id' => $ticketMessage->ticket->id,
                    'note' => "Ha respondido."
                ]);
            }
        }
    }

  
}
