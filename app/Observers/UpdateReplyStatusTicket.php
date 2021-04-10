<?php

namespace App\Observers;

use App\Models\Ticket\TicketMessage;
use App\Models\Ticket\TicketTimeline;

class UpdateReplyStatusTicket
{
    public function created(TicketMessage $ticketMessage){
        $user = $ticketMessage->user;
        
        switch ($ticketMessage->channel) {
            case 'INTERNAL':

                $ticketMessage->ticket->update([
                    "last_replied_internal_user_id" => $user->id
                ]);

            break;
        
            default:

                // user support
                if($user && $user->hasPermissionTo("portal admin")){

                    if($ticketMessage->ticket->messages->count() == 1 && !is_null($ticketMessage->ticket->reply_status_to_contract_id)){
                        $ticketMessage->ticket->update([
                            "reply_status_to_contact_id" => 7
                        ]);
                    }
                    else{
                        $ticketMessage->ticket->update([
                            "reply_status_to_contact_id" => 4,
                            "reply_status_to_users_id" => 1
                        ]); 

                    }

                }
                else{

                    if($ticketMessage->ticket->messages->count() == 1 && !is_null($ticketMessage->ticket->reply_status_to_users_id)){
                        $ticketMessage->ticket->update([
                            "reply_status_to_users_id" => 6
                        ]);
                    }
                    else{
                        $ticketMessage->ticket->update([
                            "reply_status_to_contact_id" => 2,
                            "reply_status_to_users_id" => 3
                        ]); 

                    }
                }


            break;
        }

    }

  
}
