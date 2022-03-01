<?php

namespace App\Repositories\Furniture;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\furniture\furnitureRepository;
use App\Models\Furniture\Furniture;
use App\Validators\Furniture\FurnitureValidator;

/**
 * Class FurnitureRepositoryEloquent.
 *
 * @package namespace App\Repositories\Furniture;
 */
class FurnitureRepositoryEloquent extends BaseRepository implements FurnitureRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Furniture::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
