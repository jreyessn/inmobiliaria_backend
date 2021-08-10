<?php

namespace App\Repositories\Coupons;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Coupons\CouponsRequestRepository;
use App\Models\Coupons\CouponsRequest;
use App\Validators\Coupons\CouponsRequestValidator;

/**
 * Class CouponsRequestRepositoryEloquent.
 *
 * @package namespace App\Repositories\Coupons;
 */
class CouponsRequestRepositoryEloquent extends BaseRepository implements CouponsRequestRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CouponsRequest::class;
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
        $data["user_request_id"] = request()->user()->id;

        $store = $this->create($data);

        return $store;
    }
    
    public function saveUpdate($data, $id)
    {
        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }
}
