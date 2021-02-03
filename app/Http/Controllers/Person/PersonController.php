<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use App\Repositories\Person\PersonRepositoryEloquent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonController extends Controller
{

    private $repository;

    public function __construct(
        PersonRepositoryEloquent $repository
    )
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
            'perPage'       =>  'nullable|integer',
            'page'          =>  'nullable|integer',
            'search'        =>  'nullable|string',
            'orderBy'       =>  'nullable|string',
            'sortBy'        =>  'nullable|in:desc,asc',
            'from'          =>  'nullable|date',
            'to'            =>  'nullable|date',
        ]);

        $perPage = $request->get('perPage', config('repository.pagination.limit'));

        return $this->repository->when($request->get('from', null) && $request->get('to', null), function($query) use ($request){
            $query->whereBetween('created_at', [$request->from, $request->to]);
        })->paginate($perPage);
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'occupation' => 'nullable',
            'street' => 'required',
            'city' => 'required',
            'country' => 'required',
            'postcode' => 'required|integer',
            'image' => 'required|string'
        ]);

        DB::beginTransaction();

        try{
            $data = $this->repository->create($request->all());
            
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
        $data = $this->repository->find($id);

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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'occupation' => 'nullable',
            'street' => 'required',
            'city' => 'required',
            'country' => 'required',
            'postcode' => 'required|integer',
            'image' => 'required|string'
        ]);

        DB::beginTransaction();

        try{
            $person = $this->repository->find($id);
            $person->fill($request->all());
            $person->save();
            
            DB::commit();

            return response()->json([
                "message" => "Actualización éxitosa",
            ], 201);

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

            $this->repository->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){

            return response()->json(null, 404);

        }
    }
}
