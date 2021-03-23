<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CustomerCriteria.
 *
 * @package namespace App\Criteria;
 */
class CustomerCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {

        $customers = request()->get('customer_id', null);
        $user = request()->user();
        
        if($customers){
            $explodes = explode(",", $customers);

            $model = $model->whereIn("customer_id", $explodes);
        }

        if($user->hasPermissionTo("portal customer")){
            $customer = $user->contact->customer ?? null;
            
            $model = $model->when($customer, function($query) use ($customer){
                $query->where("customer_id", $customer);
            });
        }

        return $model;
    }
}
