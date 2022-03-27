<?php

namespace App\Criteria\Customer;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AccountStatusCriteria.
 *
 * @package namespace App\Criteria\Customer;
 */
class AccountStatusCriteria implements CriteriaInterface
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

        $id = request()->get("id", null);

        if($id){
            $id    = explode(",", $id);
            $model = $model->whereIn("id", $id);
        }

        $model = $model->has("credits");

        return $model;
    }
}
