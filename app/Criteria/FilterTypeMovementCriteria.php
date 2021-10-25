<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FilterTypeMovementCriteria.
 *
 * @package namespace App\Criteria;
 */
class FilterTypeMovementCriteria implements CriteriaInterface
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
        $types = request()->get("types", null); // Venta,Devolución,Entrega

        if($types){
            $typesList = explode(",", $types);

            $model = $model->whereIn("type_movement", $typesList);
        }

        return $model;
    }
}
