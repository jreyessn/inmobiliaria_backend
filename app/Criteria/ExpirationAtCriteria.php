<?php

namespace App\Criteria;

use Carbon\Carbon;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ExpirationAtCriteria.
 *
 * @package namespace App\Criteria;
 */
class ExpirationAtCriteria implements CriteriaInterface
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
        $deadline = request()->get('deadline', null);

        if($deadline && $deadline != 'null'){
            switch ($deadline) {
                case 2:
                    $from = date('Y-m-d 00:00:00', strtotime('+1 days'));
                    $to = date('Y-m-d 23:59:59', strtotime('+1 days'));

                break;
                case 3:
                    $from = date('Y-m-d 00:00:00', strtotime('+3 days'));
                    $to = date('Y-m-d 23:59:59');
                break;
                case 4:
                    $from = Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00');
                    $to = Carbon::now()->format('Y-m-d 23:59:59');
                break;
                case 5:
                    $from = date('Y-m-d 00:00:00', strtotime('+7 days'));
                    $to = date('Y-m-d 23:59:59');
                break;
                case 6:
                    $from = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
                    $to = Carbon::now()->format('Y-m-d 23:59:59');
                break;
                case 7:
                    $from = date('Y-m-d 00:00:00', strtotime('+30 days'));
                    $to = date('Y-m-d 23:59:59');;
                break;
                default: 
                    $from = date('Y-m-d 00:00:00');
                    $to = date('Y-m-d 23:59:59');
                break;
            }
                
            $model = $model->whereBetween('deadline', [ $from, $to ])->whereHas("status_ticket", function($query){
                $query->where("can_close", 0);
            });
        }

        return $model;
    }
}
