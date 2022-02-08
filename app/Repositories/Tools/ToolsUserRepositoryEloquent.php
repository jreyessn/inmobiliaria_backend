<?php

namespace App\Repositories\Tools;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Tools\ToolsUserRepository;
use App\Models\Tools\ToolsUser;
use App\Validators\Tools\ToolsUserValidator;

/**
 * Class ToolsUserRepositoryEloquent.
 *
 * @package namespace App\Repositories\Tools;
 */
class ToolsUserRepositoryEloquent extends BaseRepository implements ToolsUserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ToolsUser::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
