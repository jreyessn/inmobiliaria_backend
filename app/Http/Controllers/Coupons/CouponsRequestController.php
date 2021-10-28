<?php

namespace App\Http\Controllers\Coupons;

use App\Criteria\CustomerCriteria;
use App\Http\Controllers\Controller;
use App\Http\Middleware\EncryptIsValid;
use App\Notifications\Coupons\ApprovedRequestCoupon;
use App\Notifications\Coupons\CustomerDeliveryCoupon;
use App\Notifications\Coupons\CustomerRequestedCoupon;
use App\Notifications\Coupons\RejectRequestCoupon;
use App\Repositories\Coupons\CouponsMovementsRepositoryEloquent;
use App\Repositories\Coupons\CouponsRequestRepositoryEloquent;
use App\Rules\IsApprovedRequestCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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
        
        $this->middleware(EncryptIsValid::class, [
            "only" => ["storeEncrypted"]
        ]);
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

        $this->couposRepository->pushCriteria(CustomerCriteria::class);

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
            $form = $request->except(["approved", "observation", "id"]);

            $data = $this->couposRepository->save($form);
            
            if($data->customer->email){
                Notification::route("mail", $data->customer->email)->notify(
                    new CustomerRequestedCoupon([
                        "folio"          => $data->folio,
                        "quantity"       => $data->quantity_coupons,
                        "encrypt_id"     => $data->customer->encrypt_id
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
     * Guardar solicitudes externas con ID de cliente cifrada
     * 
     * @param array $request["id"] ID cifrada del cliente. NO es la ID de la solicitud 
     */
    public function storeEncrypted(Request $request)
    {
        $id = decrypt($request->id);
        
        $request->merge(["customer_id" => $id]);

        return $this->store($request);
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
            $customer = $couponRequest->customer;

            if($request->get("approved") == 1){

                $couponMovement = $this->couponsMovementsRepository->save([
                    "customer_id"   => $couponRequest->customer_id,
                    "type_movement" => getMovement(1),
                    "quantity"      => $couponRequest->quantity_coupons,
                    "price"         => $couponRequest->customer->price_coupon ?? 0,
                    "is_automatic"  => 0, 
                ]);

                if($customer->email){
                    Notification::route("mail", $customer->email)->notify(
                        new ApprovedRequestCoupon([
                            "folio"          => $couponRequest->folio,
                            "quantity"       => $couponMovement->quantity,
                            "total"          => $couponMovement->total,
                            "quantity_total" => $couponMovement->customer->coupons,
                            "encrypt_id"     => $customer->encrypt_id
                        ])
                    );
                }
                
            }

            if($request->get("approved") == 2 && $customer->email){
                Notification::route("mail", $customer->email)->notify(
                    new RejectRequestCoupon([
                        "folio"          => $couponRequest->folio,
                        "observation"    => $couponRequest->observation,
                        "encrypt_id"     => $customer->encrypt_id
                    ])
                );
            }

            return response()->json([
                "message" => "Aprobación éxitosa",
            ], 201);

        }catch(\Exception $e){

            return response()->json(['message' => $e->getMessage()], 500);

        }


    }
}
