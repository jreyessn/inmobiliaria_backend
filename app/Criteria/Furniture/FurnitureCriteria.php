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
        $agent_user_id     = request()->get("agent_user_id", null);
        $type_furniture_id = request()->get("type_furniture_id", null);
        $city_id           = request()->get("city_id", null);
        $customer_id       = request()->get("customer_id", null);
        $paid              = request()->get("paid", null);
        $currency_id       = request()->get("currency_id", null);

        if($currency_id){
            $model = $model->where("currency_id", $currency_id);
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

        if($customer_id){
            $list = explode(",", $customer_id);
            $model = $model->whereIn("customer_id", $list);
        }

        if(is_null($paid) == false){
            $model = $model->whereHas("credit", function($query) use ($paid){
                $query->where("status", $paid);
            });
        }

        return $model;
    }
}
