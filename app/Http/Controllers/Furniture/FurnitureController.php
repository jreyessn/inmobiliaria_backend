<?php

namespace App\Http\Controllers\Furniture;

use App\Criteria\Furniture\FurnitureCriteria;
use App\Exports\ViewExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Furniture\FurnitureStoreRequest;
use App\Repositories\Furniture\FurnitureRepositoryEloquent;
use App\Repositories\Images\ImageRepositoryEloquent;
use App\Repositories\Sale\CreditRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;

class FurnitureController extends Controller
{
    private $FurnitureRepositoryEloquent;

    private $ImageRepositoryEloquent;

    private $CreditRepositoryEloquent;

    function __construct(
        FurnitureRepositoryEloquent $FurnitureRepositoryEloquent,
        ImageRepositoryEloquent     $ImageRepositoryEloquent,
        CreditRepositoryEloquent    $CreditRepositoryEloquent
    )
    {
        $this->FurnitureRepositoryEloquent = $FurnitureRepositoryEloquent;
        $this->ImageRepositoryEloquent     = $ImageRepositoryEloquent;
        $this->CreditRepositoryEloquent    = $CreditRepositoryEloquent;
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

        $this->FurnitureRepositoryEloquent->pushCriteria(FurnitureCriteria::class);

        switch ($request->format) {
            case 'excel':
                $data = $this->FurnitureRepositoryEloquent->get();

                return Excel::download(
                    new ViewExport ([
                        'data' => [
                            "data"  => $data,
                        ],
                        'view' => 'reports.excel.furniture'
                    ]),
                    'furnitures.xlsx'
                );
            break;
                
            case 'pdf':
                $data = $this->FurnitureRepositoryEloquent->get();

                return PDF::loadView('reports.pdf.furniture', [
                    "data"  => $data,
                ])->download('furnitures.pdf');

            break;

            default:    
                return $this->FurnitureRepositoryEloquent->paginate($perPage);
            break;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FurnitureStoreRequest $request)
    {
        DB::beginTransaction();

        try{
            $store = $this->FurnitureRepositoryEloquent->save($request->all());

            $this->ImageRepositoryEloquent->saveMany($request->file("images") ?? [], $store, [
                "path" => "furnitures"
            ]);

            if($request->get("is_credit", 0)){
                $this->CreditRepositoryEloquent->save($store, [
                    "credit_amount_anticipated"  => $request->credit_amount_anticipated,
                    "credit_interest_percentage" => $request->credit_interest_percentage,
                    "credit_cuotes"              => $request->credit_cuotes ?? []
                ]);
            }
            else{
                $dataPayment = $request->get("payment_counted", []);

                $this->CreditRepositoryEloquent->saveCounted($store, $dataPayment);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->FurnitureRepositoryEloquent->find($id)->load(["images", "credit.cuotes"]);

        return ["data" => $data];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FurnitureStoreRequest $request, $id)
    {
        $furniture = $this->FurnitureRepositoryEloquent->find($id);
        $request->validate([
            "credit_cuotes" => function($attribute, $value, $fail) use ($furniture){

                $cuoteRegistered = $furniture->credit->cuotes->slice(count($value));
                $paymentsRegistered = $cuoteRegistered->flatMap(function($cuote) {
                    return $cuote->payments;
                })->count();

                if($paymentsRegistered > 0){
                    return $fail("No puede reducir la cantidad de cuotas. Ya existen {$paymentsRegistered} pagos realizados en las cuotas que intenta eliminar");
                }
            }
        ]);

        DB::beginTransaction();

        try{
            $data = $request->all();

            $store = $this->FurnitureRepositoryEloquent->saveUpdate($data, $id);
    
            if($data["is_credit"] == 1){
                $this->CreditRepositoryEloquent->save($store, [
                    "credit_amount_anticipated"  => $request->credit_amount_anticipated,
                    "credit_interest_percentage" => $request->credit_interest_percentage,
                    "credit_cuotes"              => $request->credit_cuotes ?? []
                ]);
            }
            else{
                $credit = $this->CreditRepositoryEloquent->saveCounted($store, []);
                $this->CreditRepositoryEloquent->updateFirstPaidCounted($credit->id, $store);
            }
            
            $this->ImageRepositoryEloquent->saveMany($request->file("images") ?? [], $store, [
                "path" => "furnitures"
            ]);

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

            $furniture = $this->FurnitureRepositoryEloquent->find($id);
            
            if($furniture->credit){
                $furniture->credit->delete();
            }

            $furniture->delete();

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }
}
