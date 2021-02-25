<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class TicketFilterCriteria.
 *
 * @package namespace App\Criteria;
 */
class TicketFilterCriteria implements CriteriaInterface
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

        $type_ticket_id = request()->get('type_ticket_id', null);
        $status_ticket_id = request()->get('status_ticket_id', null);
        $priority_id = request()->get('priority_id', null);
        $group_id = request()->get('group_id', null);
        $user_id = request()->get('user_id', null);
        $contact_id = request()->get('contact_id', null);
        $customer_id = request()->get('customer_id', null);

        if($type_ticket_id){
            $explodes = explode(",", $type_ticket_id);

            $model = $model->whereIn('type_ticket_id', $explodes);
        }
        if($status_ticket_id){
            $explodes = explode(",", $status_ticket_id);

            $model = $model->whereIn('status_ticket_id', $explodes);
        }
        if($priority_id){
            $explodes = explode(",", $priority_id);

            $model = $model->whereIn('priority_id', $explodes);
        }
        if($group_id){
            $explodes = explode(",", $group_id);

            $model = $model->whereIn('group_id', $explodes);
        }
        if($type_ticket_id){
            $explodes = explode(",", $type_ticket_id);

            $model = $model->whereIn('type_ticket_id', $explodes);
        }
        if($user_id){
            $explodes = explode(",", $user_id);

            $model = $model->whereIn('user_id', $explodes);
        }
        if($contact_id){
            $explodes = explode(",", $contact_id);

            $model = $model->whereIn('contact_id', $explodes);
        }
        if($customer_id){
            $explodes = explode(",", $customer_id);

            $model = $model->whereIn('customer_id', $explodes);
        }


        return $model;
    }
}
