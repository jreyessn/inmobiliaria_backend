<?php

namespace App\Http\Controllers\Sale;

use App\Criteria\Sale\SaleCriteria;
use App\Criteria\SinceUntilCreatedAtCriteria;
use App\Exports\ViewExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\SalesStoreRequest;
use App\Repositories\Sale\CreditRepositoryEloquent;
use App\Repositories\Sale\SaleRepositoryEloquent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;

class SaleController extends Controller
{
    private $SaleRepositoryEloquent;

    private $CreditRepositoryEloquent;

    function __construct(
        SaleRepositoryEloquent $SaleRepositoryEloquent,
        CreditRepositoryEloquent $CreditRepositoryEloquent
    )
    {
        $this->SaleRepositoryEloquent = $SaleRepositoryEloquent;
        $this->CreditRepositoryEloquent = $CreditRepositoryEloquent;
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

        $this->SaleRepositoryEloquent->pushCriteria(SinceUntilCreatedAtCriteria::class);
        $this->SaleRepositoryEloquent->pushCriteria(SaleCriteria::class);
        
        switch ($request->format) {
            case 'excel':
                $data = $this->SaleRepositoryEloquent->get();

                return Excel::download(
                    new ViewExport ([
                        'data' => [
                            "data"  => $data,
                            "since" => $request->since? Carbon::parse($request->since) : null,
                            "until" => $request->until? Carbon::parse($request->until) : null,
                        ],
                        'view' => 'reports.excel.sale'
                    ]),
                    'sales.xlsx'
                );
            break;
                
            case 'pdf':
                $data = $this->SaleRepositoryEloquent->get();

                return PDF::loadView('reports.pdf.sale', [
                    "data"  => $data,
                    "since" => $request->since? Carbon::parse($request->since) : null,
                    "until" => $request->until? Carbon::parse($request->until) : null,
                ])->download('sales.pdf');

            break;

            default:    
                return $this->SaleRepositoryEloquent->paginate($perPage);
            break;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SalesStoreRequest $request)
    {

        DB::beginTransaction();

        try{

            $request->merge([
                "total" => sum_amount_tax($request->subtotal, $request->tax_percentage)
            ]);

            $store = $this->SaleRepositoryEloquent->save($request->all());

            if($request->get("is_credit", null) && $request->is_credit == 1){
                $this->CreditRepositoryEloquent->save($store, [
                    "credit_amount_anticipated"  => $request->credit_amount_anticipated,
                    "credit_interest_percentage" => $request->credit_interest_percentage,
                    "credit_cuotes"              => $request->credit_cuotes ?? []
                ]);
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
        return [
            "data" => $this->SaleRepositoryEloquent->find($id)->load([
                "credit.cuotes",
                "furniture"
            ])
        ];
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

            $this->SaleRepositoryEloquent->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }
}
