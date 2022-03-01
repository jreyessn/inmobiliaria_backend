<?php

namespace App\Repositories\Country;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\country\cityRepository;
use App\Models\Country\City;
use App\Validators\Country\CityValidator;

/**
 * Class CityRepositoryEloquent.
 *
 * @package namespace App\Repositories\Country;
 */
class CityRepositoryEloquent extends BaseRepository implements CityRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return City::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
