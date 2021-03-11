<?php

namespace App\Observers;

use App\Models\Contact\Contact;
use App\Models\Group\Group;
use App\Models\Priority;
use App\Models\StatusTicket;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketTimeline as Timeline;
use App\Models\TypeTicket;
use App\Models\User;

class TicketTimeline
{
    public function created(Ticket $ticket){

        Timeline::create([
            "ticket_id" => $ticket->id,
            "made_by_user" => request()->user()->id ?? null,
            "note" => "Ha abierto el Ticket"
        ]);

    }
    
    public function updated(Ticket $ticket){
       
        // change contact
        if($ticket->getOriginal("contact_id") !== $ticket->contact_id){
            $original = Contact::find($ticket->getOriginal("contact_id"));

            $note = '';

            if($original && $ticket->contact)
                $note = "Ha cambiado el contacto de <strong>{$original->name}</strong> a <strong>{$ticket->contact->name}</strong>.";
                
            if($original && $ticket->contact == null)
                $note = "Ha removido al contacto <strong>{$original->name}</strong>.";
                
            if($original == null && $ticket->contact)
                $note = "Ha asignado el ticket al contacto <strong>{$ticket->contact->name}</strong>.";

            if($note)
                Timeline::create([
                    "ticket_id" => $ticket->id,
                    "made_by_user" => request()->user()->id,
                    "note" => $note
                ]);
        }
        
        // change type
        if($ticket->getOriginal("type_ticket_id") !== $ticket->type_ticket_id){
            $original = TypeTicket::find($ticket->getOriginal("type_ticket_id"));

            $note = '';

            if($original && $ticket->type_ticket)
                $note =  "Ha cambiado el tipo de ticket de <strong>{$original->description}</strong> a <strong>{$ticket->type_ticket->description}</strong>.";
                
            if($original && $ticket->type_ticket == null)
                $note = "Ha removido el tipo de ticket <strong>{$original->name}</strong>.";
                
            if($original == null && $ticket->type_ticket)
                $note = "Ha actualizado el tipo de ticket a <strong>{$ticket->type_ticket->name}</strong>.";

            if($note)
                Timeline::create([
                    "ticket_id" => $ticket->id,
                    "made_by_user" => request()->user()->id,
                    "note" => $note
                ]);

        }

        // change group
        if($ticket->getOriginal("group_id") !== $ticket->group_id){
            $original = Group::find($ticket->getOriginal("group_id"));

            $note = '';

            if($original && $ticket->group)
                $note = "Ha cambiado el grupo de <strong>{$original->name}</strong> a <strong>{$ticket->group->name}</strong>.";
                
            if($original && $ticket->group == null)
                $note = "Ha removido al grupo <strong>{$original->name}</strong>.";
                
            if($original == null && $ticket->group)
                $note = "Ha asignado el ticket al grupo <strong>{$ticket->group->name}</strong>.";

            if($note)
                Timeline::create([
                    "ticket_id" => $ticket->id,
                    "made_by_user" => request()->user()->id,
                    "note" => $note
                ]);
        }

        // change user
        if($ticket->getOriginal("user_id") !== $ticket->user_id){
            $original = User::find($ticket->getOriginal("user_id"));

            $note = '';

            if($original && $ticket->user)
                $note = "Ha cambiado el usuario de <strong>{$original->name}</strong> a <strong>{$ticket->user->name}</strong>.";
                
            if($original && $ticket->user == null)
                $note = "Ha removido al usuario <strong>{$original->name}</strong>.";
                
            if($original == null && $ticket->user)
                $note = "Ha asignado el ticket al usuario <strong>{$ticket->user->name}</strong>.";

            if($note)
                Timeline::create([
                    "ticket_id" => $ticket->id,
                    "made_by_user" => request()->user()->id,
                    "note" => $note
                ]);
        }

        // change user
        if($ticket->getOriginal("priority_id") !== $ticket->priority_id){
            $original = Priority::find($ticket->getOriginal("priority_id"));

            $note = '';

            if($original && $ticket->priority)
                $note = "Ha cambiado la prioridad de <strong>{$original->description}</strong> a <strong>{$ticket->priority->description}</strong>.";
                
            if($original && $ticket->priority == null)
                $note = "Ha removido la prioridad <strong>{$original->name}</strong>.";
                
            if($original == null && $ticket->priority)
                $note = "Ha actualizado la prioridad a <strong>{$ticket->priority->name}</strong>.";

            if($note)
                Timeline::create([
                    "ticket_id" => $ticket->id,
                    "made_by_user" => request()->user()->id,
                    "note" => $note
                ]);
        }

        // change status
        if($ticket->getOriginal("status_ticket_id") !== $ticket->status_ticket_id){
            $original = StatusTicket::find($ticket->getOriginal("status_ticket_id"));

            $note = '';

            if($original && $ticket->status_ticket)
                $note = "Ha cambiado el Estado de Ticket de <strong>{$original->description}</strong> a <strong>{$ticket->status_ticket->description}</strong>.";
                
            if($original && $ticket->status_ticket == null)
                $note = "Ha removido el Estado de Ticket <strong>{$original->description}</strong>.";
                
            if($original == null && $ticket->status_ticket)
                $note = "Ha actualizado el Estado de Ticket a <strong>{$ticket->status_ticket->name}</strong>.";

            Timeline::create([
                "ticket_id" => $ticket->id,
                "made_by_user" => request()->user()->id,
                "note" => $note
            ]);
        }

        // change deadline
        if($ticket->getOriginal("deadline") !== $ticket->deadline){
            $original = $ticket->getOriginal("deadline");
            
            $note = '';

            if($original && $ticket->deadline)
                $note = "Ha cambiado el plazo de <strong>{$original}</strong> a <strong>{$ticket->deadline}</strong>.";
                
            if($original && $ticket->deadline == null)
                $note = "Ha removido el plazo <strong>{$original}</strong>.";
                
            if($original == null && $ticket->deadline)
                $note = "Ha actualizado el plazo a <strong>{$ticket->deadline}</strong>.";

            if($note)
                Timeline::create([
                    "ticket_id" => $ticket->id,
                    "made_by_user" => request()->user()->id,
                    "note" => $note
                ]);
        }

        // change tracker
        if(
            $ticket->getOriginal("tracked_initial_time") !== $ticket->tracked_initial_time || 
            $ticket->getOriginal("tracked_end_time") !== $ticket->tracked_end_time)
        {

            $original = $ticket->getOriginal("diff_tracked");;
            $new = $ticket->diff_tracked;

            $note = '';

            if($original == "Sin definir" && $new != $original)
                $note = "Ha registrado un tiempo de <strong>{$ticket->diff_tracked}</strong>.";
                
            if($original != "Sin definir" && $new == "Sin definir")
                $note = "Ha removido el tiempo registrado de <strong>{$ticket->diff_tracked}</strong>.";
                
            if($original != "Sin definir" && $new != "Sin definir")
                $note = "Ha cambiado el tiempo registrado de <strong>{$original}</strong> a <strong>{$ticket->diff_tracked}</strong>.";
                

            if($note)
                Timeline::create([
                    "ticket_id" => $ticket->id,
                    "made_by_user" => request()->user()->id,
                    "note" => $note
                ]);
        }

    }
    
    public function deleted(Ticket $ticket){


    }

}
