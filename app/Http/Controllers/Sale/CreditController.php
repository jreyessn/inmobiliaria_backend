<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
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

    function __construct(
        CreditPaymentRepositoryEloquent $CreditPaymentRepositoryEloquent,
        CreditRepositoryEloquent $CreditRepositoryEloquent
    )
    {
        $this->CreditPaymentRepositoryEloquent = $CreditPaymentRepositoryEloquent;
        $this->CreditRepositoryEloquent        = $CreditRepositoryEloquent;
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return [
            "data" => $this->CreditRepositoryEloquent->find($id)->load(["sale", "cuotes.payments"])
        ];
    }

}
