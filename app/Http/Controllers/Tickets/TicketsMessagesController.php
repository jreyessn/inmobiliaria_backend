<?php

namespace App\Http\Controllers\Tickets;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Ticket\TicketMessage;
use App\Models\User;
use App\Repositories\Ticket\TicketMessageRepositoryEloquent;
use App\Repositories\Ticket\TicketRepositoryEloquent;
use Illuminate\Support\Facades\DB;
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

            $this->ticketsMessagesRepository->save($data, $user->id ?? null);

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
