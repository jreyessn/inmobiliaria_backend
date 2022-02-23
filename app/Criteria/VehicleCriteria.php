<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class VehicleCriteria.
 *
 * @package namespace App\Criteria;
 */
class VehicleCriteria implements CriteriaInterface
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
        $vehicles = request()->get("vehicle_id", null);

        if($vehicles){
            $list = explode(",", $vehicles);

            $model = $model->whereHas("vehicle", function($query) use ($list){
                $query->whereIn("id", $list);
            });
        }

        return $model;
    }
}
