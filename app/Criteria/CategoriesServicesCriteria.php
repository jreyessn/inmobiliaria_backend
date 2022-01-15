<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CategoriesServicesCriteria.
 *
 * @package namespace App\Criteria;
 */
class CategoriesServicesCriteria implements CriteriaInterface
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
        $categories_service_id = request()->get("categories_service_id", null);

        if($categories_service_id){
            $model = $model->where("categories_service_id", $categories_service_id);
        }

        return $model;
    }
}
