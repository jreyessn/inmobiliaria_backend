<?php

namespace App\Repositories\Furniture;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\furniture\urbanitationRepository;
use App\Models\Furniture\Urbanitation;
use App\Validators\Furniture\UrbanitationValidator;

/**
 * Class UrbanitationRepositoryEloquent.
 *
 * @package namespace App\Repositories\Furniture;
 */
class UrbanitationRepositoryEloquent extends BaseRepository implements UrbanitationRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Urbanitation::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
