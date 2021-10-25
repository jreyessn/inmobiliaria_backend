<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Repositories\Customer\CustomerRepositoryEloquent;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CustomerController extends Controller
{

    private $customerRepository;

    function __construct(
        CustomerRepositoryEloquent $customerRepository
    )
    {
        $this->customerRepository = $customerRepository;
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

        return $this->customerRepository->paginate($perPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        DB::beginTransaction();

        try{
            
            $data = $this->customerRepository->save($request->all());
            
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
        $visit_user_id = request()->get("visit_user_id", null);

        $data = $this->customerRepository->find($id)->load("subscriptions");

        if($visit_user_id){
            $data->visits = $data->visits()->where("user_id", $visit_user_id)->get();
        }

        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        DB::beginTransaction();

        try{
            $this->customerRepository->saveUpdate($request->all(), $id);
            
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

            $this->customerRepository->delete($id);

            return response()->json(null, 204);

        }catch(\Exception $e){

            return response()->json(null, 404);

        }
    }

    /**
     * Muestra el QR en base_64
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function qr($id){
        $customer = $this->customerRepository->find($id);

        if(is_null($customer))
            return null;

        $format = "customer|{$customer->id}|{$customer->tradename}|{$customer->business_name}|{$customer->coupons}";
          
        return response(QrCode::format("png")->size(400)->generate($format))->header('Content-Type', 'image/png');
    }

}
