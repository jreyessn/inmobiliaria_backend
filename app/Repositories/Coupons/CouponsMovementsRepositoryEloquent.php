<?php

namespace App\Repositories\Coupons;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Coupons\CouponsMovementsRepository;
use App\Models\Coupons\CouponsMovements;
use App\Validators\Coupons\CouponsMovementsValidator;
use Illuminate\Support\Facades\Storage;

/**
 * Class CouponsMovementsRepositoryEloquent.
 *
 * @package namespace App\Repositories\Coupons;
 */
class CouponsMovementsRepositoryEloquent extends BaseRepository implements CouponsMovementsRepository
{

    protected $fieldSearchable = [
        'id' => 'like',
        'customer.tradename' => 'like',
        'customer.business_name' => 'like',
        'quantity' => 'like',
        'user_created.name' => 'like',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CouponsMovements::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function save($data)
    {
        // Si es diferente a ajuste, se define si es entrada o salida el tipo de movimiento
        if($data["type_movement"] != getMovement(4)){
            $data["io"] = getIo($data["type_movement"]);
        }
    
        if(array_key_exists("sign_customer", $data) && $data["sign_customer"]){
            $data["sign_customer"] = $this->saveSignCustomerBase64($data["sign_customer"]);
        }

        $store = $this->create($data);
        $store->price = $store->customer->price_coupon;
        $store->save();
        
        return $store;
    }

    public function saveSignCustomerBase64($image64){

        $file_name = 'sign_' . rand(0,100000) . '.png';
        $image = preg_replace('/data:image\/(.*?);base64,/','',$image64);

        Storage::disk('local')->put("customers_sign/".$file_name, base64_decode($image));
        
        return $file_name;
    }
    
}
