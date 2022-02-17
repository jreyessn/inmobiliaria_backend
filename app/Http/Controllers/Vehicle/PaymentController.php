<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Repositories\Vehicle\PaymentRepositoryEloquent;
use App\Rules\KmLessThat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private $PaymentRepositoryEloquent;

    function __construct(
        PaymentRepositoryEloquent $PaymentRepositoryEloquent
    )
    {
        $this->PaymentRepositoryEloquent = $PaymentRepositoryEloquent;
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

        return $this->PaymentRepositoryEloquent->paginate($perPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            "vehicle_id"    => ["required", "exists:vehicles,id", new KmLessThat($request->km_current)],
            "concept"       => "required|string|max:200",
            "km_current"    => "required|numeric|min:0",
            "date"          => "required|date",
            "amount"        => "required|numeric|min:0",
            "note"          => "nullable|string",
        ]);

        DB::beginTransaction();

        try{
            
            $data = $this->PaymentRepositoryEloquent->save($request->all());
            
            DB::commit();

            return response()->json([
                "message" => "Registro éxitoso",
                "data" => $data
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
        $data = $this->PaymentRepositoryEloquent->find($id)->load([
            "vehicle",
        ]);

        return ["data" => $data];
    }

    /**
     * Los pagos se actualizan. Cuándo es un pago anterior al ultimo (del vehiculo), no se validará los kilometros
     * puesto que el usuario no puede actualizarlos
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $paymentCurrent = $this->PaymentRepositoryEloquent->find($id);
        $rulesVehicle   = ["required", "exists:vehicles,id", ];

        if($paymentCurrent && $paymentCurrent->is_last_payment){
            array_push($rulesVehicle, new KmLessThat($request->km_current));
        }

        $request->validate([
            "vehicle_id"    => $rulesVehicle,
            "concept"       => "required|string|max:200",
            "km_current"    => "required|numeric|min:0",
            "date"          => "required|date",
            "amount"        => "required|numeric|min:0",
            "note"          => "nullable|string",
        ]);

        DB::beginTransaction();

        try{

            $data           = $paymentCurrent->is_last_payment? $request->all() : $request->except(["km_current"]);

            $this->PaymentRepositoryEloquent->saveUpdate($data, $id);
            
            DB::commit();

            return response()->json([
                "message" => "Actualización éxitosa",
            ], 200);

        }catch(\Exception $e){
            DB::rollback();

            return response()->json(['message' => $e->getMessage()], 500);
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

            $this->PaymentRepositoryEloquent->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }
}
