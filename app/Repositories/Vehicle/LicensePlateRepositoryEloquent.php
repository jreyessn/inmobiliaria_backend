<?php

namespace App\Repositories\Vehicle;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Vehicle\LicensePlateRepository;
use App\Models\Vehicle\LicensePlate;
use App\Validators\Vehicle\LicensePlateValidator;

/**
 * Class LicensePlateRepositoryEloquent.
 *
 * @package namespace App\Repositories\Vehicle;
 */
class LicensePlateRepositoryEloquent extends BaseRepository implements LicensePlateRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return LicensePlate::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
