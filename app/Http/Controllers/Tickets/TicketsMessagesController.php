<?php

namespace App\Http\Controllers\Tickets;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Ticket\TicketMessage;
use App\Models\User;
use App\Notifications\Tickets\NewReplyTicket;
use App\Repositories\Ticket\TicketMessageRepositoryEloquent;
use App\Repositories\Ticket\TicketRepositoryEloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class TicketsMessagesController extends Controller
{
    
    private $ticketsMessagesRepository;
    private $ticketsRepository;

    function __construct(
        TicketMessageRepositoryEloquent $ticketsMessagesRepository,
        TicketRepositoryEloquent $ticketsRepository
    )
    {
        $this->ticketsMessagesRepository = $ticketsMessagesRepository;
        $this->ticketsRepository = $ticketsRepository;
    }

    public function messageAdmin(Request $request)
    {
        $request->validate([
            "ticket_id" => "required:exists:tickets",
            "message" => "required",
            "cc" => "nullable",
            "files" => "nullable|array",
            "channel" => "required|in:INTERNAL,CUSTOMER"
        ]);

        $user = $request->user();
        $data = $request->all();

        $ticket = $this->ticketsRepository->find($data["ticket_id"]);

        if(is_null($ticket->first_reply_time)){
            $ticket->first_reply_time = now();
            $ticket->save();
        }

        if(is_null($ticket->attended_by_user)){
            $ticket->attended_by_user_id = $user->id;
            $ticket->save();
        }

        return $this->message($user, $data);
    }

    public function messageCustomer(Request $request)
    {
        $request->validate([
            "ticket_id" => "required",
            "message" => "required",
            "cc" => "nullable",
            "files" => "nullable|array"
        ]);

        $data = $request->all();
        $data["ticket_id"] = descryptId($data["ticket_id"]);

        try {
            $ticket = $this->ticketsRepository->find($data["ticket_id"]);

        } catch (\Throwable $th) {
            return response()->json(null, 404);
        }

        $user = null;

        if($request->user()){
            $user = $request->user();
        }

        if($ticket->contact->user ?? false){
            $user = $ticket->contact->user;
        }

        return $this->message($user, $data);
    }

    private function message($user, array $data)
    {
        DB::beginTransaction();
        
        try{

            $ticketMessage = $this->ticketsMessagesRepository->save($data, $user->id ?? null);

            $paramsNotify = [
                "title" => $ticketMessage->ticket->title, 
                "id_encrypted" => $ticketMessage->ticket->encript_id, 
                "id" => $ticketMessage->ticket_id, 
                "message" => $ticketMessage->message
            ];

            if($ticketMessage->channel == "INTERNAL"){
                $userAssigned = $ticketMessage->ticket->assigned;
                $userAttended = $ticketMessage->ticket->attended_by_user;
                $paramsNotify["name"] = $user->name;

                $userAssignedSend = $userAssigned->firstWhere('id', $user->id);

                /**
                 * Se notifica al usuario que atiende si el usuario asignado es el que ha enviado el mensaje
                 */
                if($userAssignedSend){
                    $userAttended->notify(new NewReplyTicket($paramsNotify));
                }
                /**
                 * Se notifica al usuario asignado si el usuario que atiende ha enviado el mensaje
                 */
                else if(($userAttended->id ?? null) == $user->id){
                    Notification::send($userAssigned, new NewReplyTicket($paramsNotify));
                }
                /**
                 * Un tercero contestó en el canal interno y se notifica a ambos usuarios
                 */
                else{
                    if($userAttended && $user->id != $userAttended->id)
                        $userAttended->notify(new NewReplyTicket($paramsNotify));
                    
                    if(is_null($userAssigned))
                        Notification::send($userAssigned, new NewReplyTicket($paramsNotify));

                }
            }
            else{
                $paramsNotify["name"] = $user->name ?? "Cliente";
                $userAttended = $ticketMessage->ticket->attended_by_user;
                $contactUser = $ticketMessage->ticket->contact->user ?? null;

                /**
                 * Si notifica al contacto si el usuario que atiende ha contestado (siempre que este tenga un usuario activo y el contacto tambien)
                 */
                if($user && $contactUser && ( $user->hasPermissionTo('portal admin') ?? null )){
                        $contactUser->notify(new NewReplyTicket($paramsNotify));
                }
                else{
                    if($userAttended)
                        $userAttended->notify(new NewReplyTicket($paramsNotify));
                }
            }

            DB::commit();

            return response()->json([
                "message" => "Mensaje enviado con éxito",
                "data" => $data
            ], 201);

        }catch(\Exception $e){
            DB::rollback();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function downloadAttach($id)
    {
        $file = File::find($id);

        return Storage::disk('local')->download('files/'.$file->name, $file->name);

    }

    public function showMessages($ticket_id, Request $request){
        $ticket_id = descryptId($ticket_id);
        $channel = $request->get("channel", "CUSTOMER");

        return [
            'data' => $this->ticketsMessagesRepository->getMessages($ticket_id, $channel)
        ];
    }

}
