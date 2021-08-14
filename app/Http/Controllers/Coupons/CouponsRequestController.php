<?php

namespace App\Http\Controllers\Coupons;

use App\Http\Controllers\Controller;
use App\Repositories\Coupons\CouponsMovementsRepositoryEloquent;
use App\Repositories\Coupons\CouponsRequestRepositoryEloquent;
use App\Rules\IsApprovedRequestCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponsRequestController extends Controller
{

    private $couposRepository;
    private $couponsMovementsRepository;

    public function __construct(
        CouponsRequestRepositoryEloquent $couposRepository,
        CouponsMovementsRepositoryEloquent $couponsMovementsRepository
    )
    {
        $this->couposRepository = $couposRepository;    
        $this->couponsMovementsRepository = $couponsMovementsRepository;    
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

        return $this->couposRepository->paginate($perPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                "customer_id" => "required|exists:customers,id",
                "quantity_coupons" => "required|min:1|numeric"
            ]
        );

        DB::beginTransaction();

        try{
            
            $data = $this->couposRepository->save($request->all());
            
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
        return $this->couposRepository->find($id)->load("customer", "user_request");
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
        $request->validate(
            [
                "customer_id" => ["required", "exists:customers,id", new IsApprovedRequestCoupon($id)],
                "quantity_coupons" => "required|min:1|numeric"
            ]
        );

        DB::beginTransaction();

        try{
            
            $data = $this->couposRepository->saveUpdate($request->all(), $id);
            
            DB::commit();

            return response()->json([
                "message" => "Actualización éxitosa",
                "data" => $data
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

            $this->couposRepository->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){

            return response()->json(null, 404);

        }
    }

    /**
     * Se aprueba el cupón y se genera un nuevo movimiento
     */
    public function approver(Request $request, $id)
    {
        $request->validate([
            "approved" => ["required", "numeric", new IsApprovedRequestCoupon($id)],
            "observation" => "nullable|string"
        ]);

        try{

            $couponRequest = $this->couposRepository->find($id);
            $couponRequest->fill([
                "approved"    => $request->get("approved"),
                "approved_at" => Carbon::now(),
                "observation" => $request->get("observation") ?? null
            ]);
            $couponRequest->save();

            if($request->get("approved") == 1){

                $this->couponsMovementsRepository->save([
                    "customer_id"   => $couponRequest->customer_id,
                    "type_movement" => "Compra",
                    "quantity"      => $couponRequest->quantity_coupons,
                    "price"         => $couponRequest->customer->price_coupon ?? 0,
                    "is_automatic"  => 0, 
                ]);
                
            }

            return response()->json([
                "message" => "Aprobación éxitosa",
            ], 201);

        }catch(\Exception $e){

            return response()->json(['message' => $e->getMessage()], 500);

        }


    }
}
