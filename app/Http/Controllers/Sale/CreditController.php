<?php

namespace App\Http\Controllers\Sale;

use App\Criteria\Sale\CreditCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\Sale\CreditCuoteRepositoryEloquent;
use App\Repositories\Sale\CreditPaymentRepositoryEloquent;
use App\Repositories\Sale\CreditRepositoryEloquent;
use App\Rules\Credit\AmountLessCuote;
use App\Rules\Credit\CuoteValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditController extends Controller
{
    private $CreditPaymentRepositoryEloquent;

    private $CreditRepositoryEloquent;

    private $CreditCuoteRepositoryEloquent;

    function __construct(
        CreditPaymentRepositoryEloquent $CreditPaymentRepositoryEloquent,
        CreditRepositoryEloquent $CreditRepositoryEloquent,
        CreditCuoteRepositoryEloquent $CreditCuoteRepositoryEloquent
    )
    {
        $this->CreditPaymentRepositoryEloquent = $CreditPaymentRepositoryEloquent;
        $this->CreditRepositoryEloquent        = $CreditRepositoryEloquent;
        $this->CreditCuoteRepositoryEloquent   = $CreditCuoteRepositoryEloquent;
    }

    /**
    * Listar créditos
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

        $this->CreditRepositoryEloquent->pushCriteria(CreditCriteria::class);

        return $this->CreditRepositoryEloquent->paginate($perPage);
    }

    /**
    * Totaliza el general de los créditos
    */
    public function totals(Request $request)
    {
       $total     = $this->CreditRepositoryEloquent
                     ->selectRaw("sum(total) as total_credit")
                     ->first()
                     ->total_credit ?? 0;

       $total_paid = $this->CreditRepositoryEloquent
                        ->selectRaw("sum(credit_payments.amount) as total_paid")
                        ->join("credit_cuotes", "credit_cuotes.credit_id", "=", "credits.id", "left")
                        ->join("credit_payments", "credit_payments.credit_cuote_id", "=", "credit_cuotes.id", "left")
                        ->first()
                        ->total_paid ?? 0;

        $total_debs = $total - $total_paid; 
        
        return [
            "total" => $total,
            "total_paid" => $total_paid,
            "total_debs" => $total_debs,
        ];
    }

    /**
     * Pagar una cuota de crédito
     *
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request, $credit_cuote_id)
    {
        $request->merge(["credit_cuote_id" => $credit_cuote_id]);

        $request->validate([
            'credit_cuote_id'   => new CuoteValid,
            'amount'            => [
                'required',
                'numeric',
                new AmountLessCuote($request->credit_cuote_id)
            ],
            'payment_method_id' => 'required|exists:payment_methods,id',
            "note"              => "nullable|string"
        ]);

        DB::beginTransaction();

        try{
            
            $store = $this->CreditPaymentRepositoryEloquent->save($request->all());

            DB::commit();

            return response()->json([
                "message" => "Pago realizado con éxito",
                "data" => $store
            ], 201);

        }catch(\Exception $e){
            DB::rollback();
            
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Actualizar cuota de referencia
     *
     * @return \Illuminate\Http\Response
     */
    public function patchCuoteReference(Request $request, $credit_cuote_id)
    {

        DB::beginTransaction();

        try{
            
            $store = $this->CreditCuoteRepositoryEloquent->find($credit_cuote_id);
            $store->reference = $request->reference ?? '';
            $store->save();

            DB::commit();

            return response()->json([
                "message" => "Actualizado con éxito",
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
        return [
            "data" => $this->CreditRepositoryEloquent->find($id)->load(["cuotes.payments"])
        ];
    }

}
