<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\Users\UserRepositoryEloquent;

class UserController extends Controller
{
    /**
     * @var $repository
     */
    protected $repository;

    /**
     * @var $responseCode
     */
    protected $responseCode = 200;

    public function __construct(UserRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'per_page'      =>  'nullable|integer',
            'page'          =>  'nullable|integer',
            'search'        =>  'nullable|string',
            'orderBy'       =>  'nullable|string',
            'sortBy'        =>  'nullable|in:desc,asc'
        ]);

        return $this->repository->with('roles')->whereHas('roles', function($query){
            $query->where('role_id', '!=', 2);
        })->customPaginate();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        DB::beginTransaction();

        $user = [];

        try{
            $user = $this->repository->create($request->all());
            $roles = $request->roles;

            // si es un rol diferente a compras y proveedor, se le asigna el rol especial
            // de autorizaciones a sap

            if(!in_array(2, $request->roles) && !in_array(3, $request->roles)){
                array_push($roles, 8);
            }

            $user->roles()->sync($roles);
            
            DB::commit();

            return response()->json([
                "message" => "Registro éxitoso",
                "data" => $user
            ], 201);

        }catch(\Exception $e){
            DB::rollback();

            return response()->json(['message' => $e->getMessage()], 500);

        }

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->repository->with(['roles.permissions', 'type_provider'])->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserUpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserCreateRequest $request, $id)
    {
        
        DB::beginTransaction();

        try{
            $user = $this->repository->find($id);
            $user->fill( $request->all() );

            if($request->has('password'))
                $user->password_changed_at = date('Y-m-d H:i:s');
            
            $user->save();
            $user->roles()->sync( $request->roles );

            DB::commit();

            return response()->json([
                "message" => "Actualización exitosa",
                "data" => $user
            ], 201);

        }catch(\Exception $e){
            DB::rollback();

            return response()->json(['message' => $e->getMessage()], 500);
        }

 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{

            $user = $this->repository->find($id);
            $user->delete();

            return response()->json(null, 204);

        }catch(\Exception $e){
            return response()->json(null, 404);
        }


    }
}
