<?php

namespace App\Repositories\Reports;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Reports\ReportRepository;
use App\Models\Reports\Report;
use App\Validators\Reports\ReportValidator;

/**
 * Class ReportRepositoryEloquent.
 *
 * @package namespace App\Repositories\Reports;
 */
class ReportRepositoryEloquent extends BaseRepository implements ReportRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Report::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
