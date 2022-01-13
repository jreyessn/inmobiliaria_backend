<?php

namespace App\Repositories\Services;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Services\SparePartRepository;
use App\Models\Services\SparePart;
use App\Validators\Services\SparePartValidator;

/**
 * Class SparePartRepositoryEloquent.
 *
 * @package namespace App\Repositories\Services;
 */
class SparePartRepositoryEloquent extends BaseRepository implements SparePartRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SparePart::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
