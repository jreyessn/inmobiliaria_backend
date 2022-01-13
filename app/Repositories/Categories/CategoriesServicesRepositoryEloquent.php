<?php

namespace App\Repositories\Categories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Categories\CategoriesServicesRepository;
use App\Models\Categories\CategoriesServices;
use App\Validators\Categories\CategoriesServicesValidator;

/**
 * Class CategoriesServicesRepositoryEloquent.
 *
 * @package namespace App\Repositories\Categories;
 */
class CategoriesServicesRepositoryEloquent extends BaseRepository implements CategoriesServicesRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CategoriesServices::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
