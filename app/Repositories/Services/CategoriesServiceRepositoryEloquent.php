<?php

namespace App\Repositories\Services;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Services\CategoriesServiceRepository;
use App\Models\Services\CategoriesService;
use App\Validators\Services\CategoriesServiceValidator;

/**
 * Class CategoriesServiceRepositoryEloquent.
 *
 * @package namespace App\Repositories\Services;
 */
class CategoriesServiceRepositoryEloquent extends BaseRepository implements CategoriesServiceRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CategoriesService::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
