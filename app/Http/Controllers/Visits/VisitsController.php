<?php

namespace App\Http\Controllers\Visits;

use App\Http\Controllers\Controller;
use App\Repositories\Visit\VisitRepositoryEloquent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitsController extends Controller
{
    private $visitRepository;

    function __construct(
        VisitRepositoryEloquent $visitRepository
    )
    {
        $this->visitRepository = $visitRepository;
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

        return $this->visitRepository->paginate($perPage);
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
            "customer_id" => "required|exists:customers,id",
            "user_id"     => "required|exists:users,id"
        ]);

        DB::beginTransaction();

        try{
            
            $data = $this->visitRepository->save($request->all());
            
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
        $data = $this->visitRepository->find($id);

        return $data;
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
            "customer_id" => "required|exists:customers,id",
            "user_id"     => "required|exists:users,id"
        ]);

        DB::beginTransaction();

        try{
            $this->visitRepository->saveUpdate($request->all(), $id);
            
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

            $this->visitRepository->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){

            return response()->json(null, 404);

        }
    }

    /**
     * Verifica si un usuario ha visitado el día actual
     */
    public function hasVisitedToday(Request $request)
    {
        $request->validate([
            "customer_id" => "required|exists:customers,id",
            "user_id"     => "required|exists:users,id"
        ]);

        $visit = $this->visitRepository
                    ->where([
                        "customer_id" => $request->customer_id,
                        "user_id"     => $request->user_id,
                    ])
                    ->whereDate("created_at", Carbon::today())
                    ->first();

        return [
            "is_visited" => $visit? true : false,
            "visit"      => $visit
        ];
    }
}
