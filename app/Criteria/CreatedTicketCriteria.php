<?php

namespace App\Criteria;

use Carbon\Carbon;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CreatedTicketCriteria.
 *
 * @package namespace App\Criteria;
 */
class CreatedTicketCriteria implements CriteriaInterface
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
        $created = request()->get('created', null);

        if($created && $created != 'null'){
            switch ($created) {
                case 2:
                    $from = date('Y-m-d 00:00:00', strtotime('-1 days'));
                    $to = date('Y-m-d 23:59:59', strtotime('-1 days'));

                break;
                case 3:
                    $from = date('Y-m-d 00:00:00', strtotime('-3 days'));
                    $to = date('Y-m-d 23:59:59');
                break;
                case 4:
                    $from = Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00');
                    $to = Carbon::now()->format('Y-m-d 23:59:59');
                break;
                case 5:
                    $from = date('Y-m-d 00:00:00', strtotime('-7 days'));
                    $to = date('Y-m-d 23:59:59');
                break;
                case 6:
                    $from = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
                    $to = Carbon::now()->format('Y-m-d 23:59:59');
                break;
                case 7:
                    $from = date('Y-m-d 00:00:00', strtotime('-30 days'));
                    $to = date('Y-m-d 23:59:59');;
                break;
                default: 
                    $from = date('Y-m-d 00:00:00');
                    $to = date('Y-m-d 23:59:59');
                break;
            }
                
            $model = $model->whereBetween('created_at', [ $from, $to ]);
        }

        return $model;
    }
}
