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
        $agent_user_id = request()->get("agent_user_id", null);
        $type_furniture_id = request()->get("type_furniture_id", null);
        $city_id = request()->get("city_id", null);

        if($is_sold){
            $model = $model->has("sale");
        }

        if($agent_user_id){
            $list = explode(",", $agent_user_id);
            $model = $model->whereIn("agent_user_id", $list);
        }

        if($type_furniture_id){
            $list = explode(",", $type_furniture_id);
            $model = $model->whereIn("type_furniture_id", $list);
        }

        if($city_id){
            $list = explode(",", $city_id);
            $model = $model->whereIn("city_id", $list);
        }

        return $model;
    }
}
