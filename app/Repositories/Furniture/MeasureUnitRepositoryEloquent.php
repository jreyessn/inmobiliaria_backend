<?php

namespace App\Repositories\Furniture;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\furniture\measure_unitRepository;
use App\Models\Furniture\MeasureUnit;
use App\Validators\Furniture\MeasureUnitValidator;

/**
 * Class MeasureUnitRepositoryEloquent.
 *
 * @package namespace App\Repositories\Furniture;
 */
class MeasureUnitRepositoryEloquent extends BaseRepository implements MeasureUnitRepository
{

    protected $fieldSearchable = [
        "name" => "like"
    ];
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MeasureUnit::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
