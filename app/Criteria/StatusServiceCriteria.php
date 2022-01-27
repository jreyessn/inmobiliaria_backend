<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class StatusServiceCriteria.
 *
 * @package namespace App\Criteria;
 */
class StatusServiceCriteria implements CriteriaInterface
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
        $status = request()->get("status", null);

        if($status !== null){
            $statusList = explode(",", $status);

            $model = $model->where(function($query) use ($statusList){
    
                foreach ($statusList as $statusItem) {
                    if($statusItem == 0){
                        $query->orWhereRaw("(now() <= date_format(event_date, '%Y-%m-%d 23:59:59') and status = 0)");
                    }
                    if($statusItem == 2){
                        $query->orWhereRaw("(now() > date_format(event_date, '%Y-%m-%d 23:59:59') and status = 0)");
                    }
                    if($statusItem == 1){
                        $query->orWhere("status", 1);
                    }
                }

            });

        }

        return $model;
    }
}
