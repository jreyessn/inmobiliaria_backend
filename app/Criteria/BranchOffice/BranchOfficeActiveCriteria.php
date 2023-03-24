<?php

namespace App\Criteria\BranchOffice;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class BranchOfficeActiveCriteria.
 *
 * @package namespace App\Criteria\BranchOffice;
 */
class BranchOfficeActiveCriteria implements CriteriaInterface
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
        $branch_office_current = request()->get("branch_office_current");

        if($branch_office_current){
            $model = $model->whereHas("branch_offices", function($query) use ($branch_office_current){
                $query->where("branch_offices.id", $branch_office_current);
            });
        }

        return $model;
    }
}
