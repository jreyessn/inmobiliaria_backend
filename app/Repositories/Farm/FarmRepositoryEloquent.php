<?php

namespace App\Repositories\Farm;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Farm\FarmRepository;
use App\Models\Farm\Farm;
use App\Validators\Farm\FarmValidator;

/**
 * Class FarmRepositoryEloquent.
 *
 * @package namespace App\Repositories\Farm;
 */
class FarmRepositoryEloquent extends BaseRepository implements FarmRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Farm::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
