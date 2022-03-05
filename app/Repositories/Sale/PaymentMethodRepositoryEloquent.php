<?php

namespace App\Repositories\Sale;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Sale\PaymentMethodRepository;
use App\Models\Sale\PaymentMethod;
use App\Validators\Sale\PaymentMethodValidator;

/**
 * Class PaymentMethodRepositoryEloquent.
 *
 * @package namespace App\Repositories\Sale;
 */
class PaymentMethodRepositoryEloquent extends BaseRepository implements PaymentMethodRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PaymentMethod::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
