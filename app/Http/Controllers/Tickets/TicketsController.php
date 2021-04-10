<?php

namespace App\Http\Controllers\Tickets;

use Illuminate\Http\Request;
use App\Criteria\ContactCriteria;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Criteria\TicketFilterCriteria;
use App\Criteria\CreatedTicketCriteria;
use App\Criteria\CreatedAtCriteriaCriteria;
use App\Criteria\ExpirationAtCriteria;
use App\Http\Requests\Tickets\TicketsStoreRequest;
use App\Http\Requests\Tickets\TicketsUpdateRequest;
use App\Repositories\Ticket\TicketRepositoryEloquent;
use App\Http\Requests\Tickets\TicketsStoreCustomerRequest;
use App\Models\System\System;
use App\Notifications\Tickets\OpenTicketToAdmin;
use App\Notifications\Tickets\OpenTicketToAssigned;
use App\Notifications\Tickets\OpenTicketToContact;
use App\Notifications\Tickets\TicketClosed;
use App\Notifications\Tickets\TicketInProgress;
use App\Notifications\Tickets\TicketResolved;
use App\Repositories\System\SystemRepositoryEloquent;
use App\Repositories\Ticket\TicketMessageRepositoryEloquent;
use App\Repositories\Users\UserRepositoryEloquent;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class TicketsController extends Controller
{

    private $ticketsRepository;
    private $ticketsMessagesRepository;
    private $userRepository;
    private $systemRepository;

    function __construct(
        TicketRepositoryEloquent $ticketsRepository,
        TicketMessageRepositoryEloquent $ticketsMessagesRepository,
        UserRepositoryEloquent $userRepository,
        SystemRepositoryEloquent $systemRepository
    )
    {
        $this->ticketsRepository = $ticketsRepository;
        $this->ticketsMessagesRepository = $ticketsMessagesRepository;
        $this->userRepository = $userRepository;
        $this->systemRepository = $systemRepository;

        $this->middleware(['CanCloseTicket'], ['only' => 'update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'perPage'       =>  'nullable|integer',
            'page'          =>  'nullable|integer',
            'search'        =>  'nullable|string',
            'orderBy'       =>  'nullable|string',
            'sortBy'        =>  'nullable|in:desc,asc',
        ]);

        $perPage = $request->get('perPage', config('repository.pagination.limit'));

        $this->ticketsRepository->pushCriteria(ContactCriteria::class);
        $this->ticketsRepository->pushCriteria(CreatedAtCriteriaCriteria::class);
        $this->ticketsRepository->pushCriteria(TicketFilterCriteria::class);
        $this->ticketsRepository->pushCriteria(CreatedTicketCriteria::class);
        $this->ticketsRepository->pushCriteria(ExpirationAtCriteria::class);

        return $this->ticketsRepository->paginate($perPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAdmin(TicketsStoreRequest $request)
    {
        DB::beginTransaction();
        
        try{
            $user = request()->user();
            $data = $request->all();
            $data["attended_by_user_id"] = $user->id;
            $data['last_replied_at'] = now();
            $store = $this->ticketsRepository->save($data);
            $assigned = $store->assigned;

            if($data["message"]){
                $data['ticket_id'] = $store->id;
                $this->ticketsMessagesRepository->save($data, $user->id);
            }
            else{
                $store->reply_status_to_users_id = 5;
                $store->reply_status_to_contact_id = 7;
                $store->save();
            }

            $paramsNotify = [
                "name" => $user->name, 
                "title" => $store->title, 
                "id_encrypted" => $store->encript_id, 
                "id" => $store->id, 
            ];

            if($store->contact)
                $store->contact->user->notify(new OpenTicketToContact($paramsNotify));
            
            $assignedCreatedTicket = $assigned->firstWhere("id", $user->id);

            // created by admin and assigned user
            if(is_null($assignedCreatedTicket)){
                FacadesNotification::send($assigned, new OpenTicketToAssigned($paramsNotify));
            }
                 
            // created by user and auto assigned. Notify to admin
            if($assignedCreatedTicket){
                $users = $this->userRepository->getAdminUsers();

                FacadesNotification::send($users, new OpenTicketToAdmin($paramsNotify));
            }

            DB::commit();

            return response()->json([
                "message" => "Registro éxitoso",
                "data" => $store
            ], 201);

        }catch(\Exception $e){
            DB::rollback();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCustomer(TicketsStoreCustomerRequest $request)
    {
        DB::beginTransaction();
        
        try{
            $data = $request->all();
            $user = $request->user();
            $data['contact_id'] = $user->contact->id;

            $store = $this->ticketsRepository->save($data);
            
            $data['ticket_id'] = $store->id;

            $this->ticketsMessagesRepository->save($data, $user->id);

            DB::commit();

            return response()->json([
                "message" => "Mensaje enviado con éxito",
                "data" => $store
            ], 201);

        }catch(\Exception $e){
            DB::rollback();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeGuest(Request $request)
    {
        $request->validate([
            "path_system" => "required",
            "type_ticket_id" => "required|exists:type_tickets,id",
            "title" => "required",
            "message" => "required"
        ]);

        DB::beginTransaction();
        
        try{
            $data = $request->all();
            $data["system_id"] = $this->systemRepository->whereNameUrl($data["path_system"])->id ?? null;
         
            $store = $this->ticketsRepository->save($data);
            
            $data['ticket_id'] = $store->id;

            $this->ticketsMessagesRepository->save($data);

            DB::commit();

            return response()->json([
                "message" => "Mensaje enviado con éxito",
                "data" => $store
            ], 201);

        }catch(\Exception $e){
            DB::rollback();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = descryptId($id);

        try {

            $data = $this->ticketsRepository->find($id)->load(
                "contact.customer",
                "user",
                "group",
                "priority",
                "status_ticket",
                "type_ticket",
                "system",
                "attended_by_user",
                "assigned"
            );
            
            return compact('data');

        } catch (\Throwable $th) {
            return response()->json(null, 404);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TicketsUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        
        try {

            $data = $request->all();
            
            $original = $this->ticketsRepository->find($id)->load("assigned");
            $found = $this->ticketsRepository->saveUpdate($data, $id);
            $user = request()->user();

            $this->notifyTo($original, $found, $user);
         
            DB::commit();

            return response()->json([
                "message" => "Actualizado con éxito"
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(null, 404);
        }

    }

    /**
     * Notificaciones al actualizar (comparar original y actualizado).
     * 
     * @param App\Model\Ticket\Ticket $original 
     * @param App\Model\Ticket\Ticket $found 
     * @param App\Model\User $user 
     */
    private function notifyTo($original, $found, $user)
    {
        
        $usersNewAssigned = $found->assigned->reject(function($assigned) use ($original){
            return $original->assigned->filter(function($assignedOriginal) use ($assigned){
                return ($assigned->id == $assignedOriginal->id);
            })->count() > 0;
        });

        if($usersNewAssigned->count() > 0){

            $paramsNotify = [
                "name" => $user->name, 
                "title" => $found->title, 
                "id" => $found->id, 
            ];

            FacadesNotification::send($usersNewAssigned, new OpenTicketToAssigned($paramsNotify));
        }

        if($original->contact_id != $found->contact_id && $found->contact){
            $paramsNotify = [
                "name" => $user->name, 
                "title" => $found->title, 
                "id_encrypted" => $found->encript_id, 
                "id" => $found->id, 
            ];

            $found->contact->user->notify(new OpenTicketToContact($paramsNotify));
        }

        if(
            ($found->status_ticket->can_close ?? null) == 1 && 
            $original->status_ticket_id != $found->status_ticket_id && 
            $found->status_ticket &&
            $found->contact
        ){
            $paramsNotify = [
                "title" => $found->title, 
                "id_encrypted" => $found->encript_id, 
                "id" => $found->id, 
                "name" => $user->name, 
                "status_text" => $found->status_ticket->description
            ];

            $found->contact->user->notify(new TicketClosed($paramsNotify));
        }

        if(
            $found->status_ticket_id == 2 && 
            $original->status_ticket_id != $found->status_ticket_id && 
            $found->status_ticket &&
            $found->contact    
        ){
            $paramsNotify = [
                "name" => $user->name, 
                "title" => $found->title, 
                "id_encrypted" => $found->encript_id, 
                "id" => $found->id, 
            ];

            $found->contact->user->notify(new TicketInProgress($paramsNotify));
        }

        if(
            $found->status_ticket_id == 3 && 
            $original->status_ticket_id != $found->status_ticket_id && 
            $found->attended_by_user_id != $user->id &&
            $found->status_ticket && 
            $found->attended_by_user
        ){

            $paramsNotify = [
                "name" => $user->name, 
                "title" => $found->title, 
                "id_encrypted" => $found->encript_id, 
                "id" => $found->id, 
            ];

            $found->attended_by_user->notify(new TicketResolved($paramsNotify));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{

            $this->ticketsRepository->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){

            return response()->json(null, 404);

        }
    }

    function tracked($ticket_id, Request $request){

        $request->validate([
            'tracked_time' => 'nullable|string',
        ]);

        $ticket = $this->ticketsRepository->find($ticket_id);
        $ticket->tracked_time = $request->tracked_time;
        $ticket->save();

        return response()->json([
            'tracked_time' => $ticket->tracked_time
        ], 200);
    }

    /**
     * Clona el mensaje para el canal interno
     * 
     * @param int $ticket_message_id ID de Ticket Message
     */
    function forwardInternal(Request $request)
    {

        $request->validate([
            "ticket_message_id" => "required"
        ]);

        $ticketMessage = $this->ticketsMessagesRepository->find($request->ticket_message_id);
        $newTicketMessage = $ticketMessage->replicate();

        $newTicketMessage->user_id = $request->user()->id;
        $newTicketMessage->forward = true;
        $newTicketMessage->channel = 'INTERNAL';
        $newTicketMessage->save();
     
        foreach ($ticketMessage->files as $file) {
            $newFile = $file->replicate();
            $newFile->model_id = $newTicketMessage->id;
            $newFile->save();
        }
     
        return [ 
            'message' => "Mensaje reenviado con éxito"     
        ];
    }
}
