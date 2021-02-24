<?php

namespace App\Http\Controllers\Systems;

use App\Http\Controllers\Controller;
use App\Repositories\System\SystemRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemsController extends Controller
{

    private $systemRepository;

    function __construct(
        SystemRepositoryEloquent $systemRepository
    )
    {
        $this->systemRepository = $systemRepository;
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

        return $this->systemRepository->paginate($perPage);
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
            'name' => 'required|string|max:100',
            'url_production' => 'nullable|string|max:200',
            'url_qa' => 'nullable|string|max:200',
            'url_admin' => 'nullable|string|max:200',
            'url_customers' => 'nullable|string|max:200',
            'active' => 'required|boolean',
            'app_mobile' => 'nullable|boolean',
            'link_download_app' => 'nullable|string|max:200',
            'backup' => 'nullable|boolean',
            'customer_id' => 'nullable|exists:customers,id',

            'credentials_users' => 'array',
            'credentials_servers' => 'array',
            'credentials_users.*.username' => 'required',
            'credentials_users.*.password' => 'required',
            'credentials_servers.*.username' => 'required',
            'credentials_servers.*.password' => 'required',
        ]);

        DB::beginTransaction();

        try{
            $data = $this->systemRepository->save($request->all());
            
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
        $data = $this->systemRepository->find($id)->load('credentials_users', 'credentials_servers', 'customer');

        return compact('data');
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
            'name' => 'required|string|max:100',
            'url_production' => 'nullable|string|max:200',
            'url_qa' => 'nullable|string|max:200',
            'url_admin' => 'nullable|string|max:200',
            'url_customers' => 'nullable|string|max:200',
            'active' => 'required|boolean',
            'app_mobile' => 'nullable|boolean',
            'link_download_app' => 'nullable|string|max:200',
            'backup' => 'nullable|boolean',
            'customer_id' => 'nullable|exists:customers,id',

            'credentials_users' => 'array',
            'credentials_servers' => 'array',
        ]);

        DB::beginTransaction();

        try{
            
            $this->systemRepository->saveUpdate($request->all(), $id);
            
            DB::commit();

            return response()->json([
                "message" => "Actualización exitosa",
            ], 202);

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

            $this->systemRepository->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){

            return response()->json(null, 404);

        }
    }
}
