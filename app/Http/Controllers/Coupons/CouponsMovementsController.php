<?php

namespace App\Http\Controllers\Coupons;

use App\Criteria\CustomerCriteria;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coupons\StoreCouponsRequest;
use App\Notifications\Coupons\CustomerPurchaseCoupon;
use App\Repositories\Coupons\CouponsMovementsRepositoryEloquent;
use App\Repositories\Customer\CustomerRepositoryEloquent;
use App\Rules\CanDeleteCouponMovement;
use App\Rules\CustomerCouponsAvailables;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class CouponsMovementsController extends Controller
{

    private $couponsMovementsRepository;


    public function __construct(
        CouponsMovementsRepositoryEloquent $couponsMovementsRepository
    )
    {
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
            'customer_id'   =>  'nullable|string',
        ]);

        $perPage = $request->get('perPage', config('repository.pagination.limit'));
        
        $this->couponsMovementsRepository->pushCriteria(CustomerCriteria::class);

        return $this->couponsMovementsRepository->paginate($perPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCouponsRequest $request)
    {
        DB::beginTransaction();

        try{
            
            $data = $this->couponsMovementsRepository->save($request->all());

            if($data->customer->email && $data->type_movement == getMovement(1)){
                FacadesNotification::route("mail", $data->customer->email)->notify(
                    new CustomerPurchaseCoupon([
                        "quantity"       => $data->quantity,
                        "quantity_total" => $data->customer->coupons,
                        "total"          => $data->total 
                    ])
                );
            }

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
        return $this->couponsMovementsRepository->find($id)->load("customer");
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        $validator = FacadesValidator::make(["id" => $id], [
            "id" => [ new CanDeleteCouponMovement($id) ]
        ]);

        if($validator->fails()){
            return response([
                "errors" => $validator->getMessageBag()
            ], 422);
        }
        
        try{
            
            $this->couponsMovementsRepository->delete($id);
            
            DB::commit();

            return response()->json(null, 204);

        }catch(\Exception $e){
            DB::rollback();
            
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
