<?php

namespace App\Repositories\Visit;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Visit\VisitRepository;
use App\Models\Visit\Visit;
use App\Validators\Visit\VisitValidator;

/**
 * Class VisitRepositoryEloquent.
 *
 * @package namespace App\Repositories\Visit;
 */
class VisitRepositoryEloquent extends BaseRepository implements VisitRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Visit::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function save(array $data)
    {
        $store = $this->create($data);

        return $store;
    }
    
    public function saveUpdate(array $data, int $id)
    {

        $store = $this->find($id);
        $store->fill($data);
        $store->save();
        
        return $store;
    }
    
}
