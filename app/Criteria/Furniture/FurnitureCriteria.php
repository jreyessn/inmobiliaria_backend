<?php

namespace App\Criteria\Furniture;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FurnitureCriteria.
 *
 * @package namespace App\Criteria\Furniture;
 */
class FurnitureCriteria implements CriteriaInterface
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
        $is_sold = request()->get("is_sold", null);

        if($is_sold){
            $model = $model->has("sale");
        }

        return $model;
    }
}
