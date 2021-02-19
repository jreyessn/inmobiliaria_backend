<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ContactCriteria.
 *
 * @package namespace App\Criteria;
 */
class ContactCriteria implements CriteriaInterface
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

        $user = request()->user();

        if($user->hasPermissionTo('portal customer')){
            $model = $model->where("contact_id", $user->contact->id);
        }

        return $model;
    }
}
