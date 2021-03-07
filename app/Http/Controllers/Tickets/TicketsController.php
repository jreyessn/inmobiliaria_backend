<?php

namespace App\Http\Controllers\Tickets;

use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use App\Criteria\GroupCriteria;
use App\Models\Contact\Contact;
use App\Criteria\ContactCriteria;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Criteria\TicketFilterCriteria;
use App\Criteria\CreatedTicketCriteria;
use App\Criteria\CreatedAtCriteriaCriteria;
use App\Http\Requests\Tickets\TicketsStoreRequest;
use App\Http\Requests\Tickets\TicketsUpdateRequest;
use App\Repositories\Ticket\TicketRepositoryEloquent;
use App\Http\Requests\Tickets\TicketsStoreCustomerRequest;
use App\Repositories\Ticket\TicketMessageRepositoryEloquent;
use Carbon\Carbon;

class TicketsController extends Controller
{

    private $ticketsRepository;
    private $ticketsMessagesRepository;

    function __construct(
        TicketRepositoryEloquent $ticketsRepository,
        TicketMessageRepositoryEloquent $ticketsMessagesRepository
    )
    {
        $this->ticketsRepository = $ticketsRepository;
        $this->ticketsMessagesRepository = $ticketsMessagesRepository;
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
            $data = $request->all();
            $store = $this->ticketsRepository->save($data);
            
            $userId = request()->user()->id;

            $data['ticket_id'] = $store->id;
            
            $this->ticketsMessagesRepository->save($data, $userId);

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
                "system"
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

            $this->ticketsRepository->saveUpdate($data, $id);

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
            'tracked_initial' => 'nullable|date',
            'tracked_end' => 'nullable|date',
        ]);

        $ticket = $this->ticketsRepository->find($ticket_id);

        if($request->tracked_initial){
            $ticket->tracked_initial_time = $request->tracked_initial;
        }
        
        if($request->tracked_end){
            $ticket->tracked_end_time = $request->tracked_end;
        }

        $ticket->save();

        return [ 
            'diffHumans' => $ticket->diff_tracked    
        ];
    }
}
