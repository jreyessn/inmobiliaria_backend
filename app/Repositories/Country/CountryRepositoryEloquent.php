<?php

namespace App\Repositories\Country;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Country\CountryRepository;
use App\Models\Country\Country;
use App\Validators\Country\CountryValidator;

/**
 * Class CountryRepositoryEloquent.
 *
 * @package namespace App\Repositories\Country;
 */
class CountryRepositoryEloquent extends BaseRepository implements CountryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Country::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
