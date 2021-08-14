<?php

namespace App\Repositories\Coupons;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Coupons\CouponsMovementsRepository;
use App\Models\Coupons\CouponsMovements;
use App\Validators\Coupons\CouponsMovementsValidator;

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
        $store = $this->create($data);
        $store->price = $store->customer->price_coupon;
        $store->save();
        
        return $store;
    }
    
}
