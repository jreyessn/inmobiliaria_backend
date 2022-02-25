<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class VehicleReportsCriteria.
 *
 * @package namespace App\Criteria;
 */
class VehicleReportsCriteria implements CriteriaInterface
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

            $model = $model->whereIn("id", $list);
        }

        return $model;
    }
}
