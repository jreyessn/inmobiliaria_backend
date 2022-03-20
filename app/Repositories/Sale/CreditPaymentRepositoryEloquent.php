<?php

namespace App\Repositories\Sale;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\sale\credit_paymentRepository;
use App\Models\Sale\CreditPayment;
use App\Validators\Sale\CreditPaymentValidator;

/**
 * Class CreditPaymentRepositoryEloquent.
 *
 * @package namespace App\Repositories\Sale;
 */
class CreditPaymentRepositoryEloquent extends BaseRepository implements CreditPaymentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CreditPayment::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    /**
     * Guardar pago
     * 
     * @param array $data
     */
    public function save($data){
        $store = $this->create($data);

        return $store;
    }
}
