<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class EquipmentCriteria.
 *
 * @package namespace App\Criteria;
 */
class EquipmentCriteria implements CriteriaInterface
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
        $equipments = request()->get("equipment_id", null);

        if($equipments){
            $list = explode(",", $equipments);

            $model = $model->whereHas("equipment", function($query) use ($list){
                $query->whereIn("equipments.id", $list);
            });
        }

        return $model;
    }
}
