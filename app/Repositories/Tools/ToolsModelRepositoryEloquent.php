<?php

namespace App\Repositories\Tools;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Tools\ToolsModelRepository;
use App\Models\Tools\ToolsModel;
use App\Validators\Tools\ToolsModelValidator;

/**
 * Class ToolsModelRepositoryEloquent.
 *
 * @package namespace App\Repositories\Tools;
 */
class ToolsModelRepositoryEloquent extends BaseRepository implements ToolsModelRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ToolsModel::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
