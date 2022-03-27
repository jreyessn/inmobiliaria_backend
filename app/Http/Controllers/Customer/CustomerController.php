<?php

namespace App\Http\Controllers\Customer;

use App\Criteria\Customer\AccountStatusCriteria;
use App\Exports\ViewExport;
use App\Http\Controllers\Controller;
use App\Repositories\Customer\CustomerRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;

class CustomerController extends Controller
{
    private $CustomerRepositoryEloquent;

    function __construct(
        CustomerRepositoryEloquent $CustomerRepositoryEloquent
    )
    {
        $this->CustomerRepositoryEloquent = $CustomerRepositoryEloquent;
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

        switch ($request->format) {
            case 'excel':
                $data = $this->CustomerRepositoryEloquent->get();

                return Excel::download(
                    new ViewExport ([
                        'data' => [
                            "data"  => $data,
                        ],
                        'view' => 'reports.excel.customers'
                    ]),
                    'customers.xlsx'
                );
            break;
                
            case 'pdf':
                $data = $this->CustomerRepositoryEloquent->get();

                return PDF::loadView('reports.pdf.customers', [
                    "data"  => $data,
                ])->download('customers.pdf');

            break;

            default:    
                return $this->CustomerRepositoryEloquent->paginate($perPage);
            break;
        }
    }

    /**
     * Listar estados de cuenta
     *
     * @return \Illuminate\Http\Response
     */
    public function account_status(Request $request)
    {
        $request->validate([
            'perPage'       =>  'nullable|integer',
            'page'          =>  'nullable|integer',
            'search'        =>  'nullable|string',
            'orderBy'       =>  'nullable|string',
            'sortBy'        =>  'nullable|in:desc,asc',
        ]);

        $perPage = $request->get('perPage', config('repository.pagination.limit'));

        $this->CustomerRepositoryEloquent->pushCriteria(AccountStatusCriteria::class);

        switch ($request->format) {
            case 'excel':
                $data = $this->CustomerRepositoryEloquent->get();

                return Excel::download(
                    new ViewExport ([
                        'data' => [
                            "data"  => $data,
                        ],
                        'view' => 'reports.excel.customers'
                    ]),
                    'customers.xlsx'
                );
            break;
                
            case 'pdf':
                $data = $this->CustomerRepositoryEloquent->get();

                return PDF::loadView('reports.pdf.customers', [
                    "data"  => $data,
                ])->download('customers.pdf');

            break;

            default:    
                return $this->CustomerRepositoryEloquent->paginateAccountStatus($perPage);
            break;
        }
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
            "name"         => "required|string|max:200|unique:customers,name,NULL,id,deleted_at,NULL",
            "dni"          => "required|string",
            "email"        => "nullable|email",
            "phone"        => "nullable|numeric",
        ]);

        DB::beginTransaction();

        try{            
            $data = $this->CustomerRepositoryEloquent->save($request->all());
            
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
        $data = $this->CustomerRepositoryEloquent->find($id);

        return ["data" => $data];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "name"         => "required|string|max:200|unique:customers,name,{$id},id,deleted_at,NULL",
            "dni"          => "required|string",
            "email"        => "nullable|email",
            "phone"        => "nullable|numeric",
        ]);
        
        DB::beginTransaction();

        try{
            $this->CustomerRepositoryEloquent->saveUpdate($request->all(), $id);
            
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

            $this->CustomerRepositoryEloquent->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }

    }
}
