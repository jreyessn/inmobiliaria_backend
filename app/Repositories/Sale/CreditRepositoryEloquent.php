<?php

namespace App\Repositories\Sale;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\sale\creditRepository;
use App\Models\Sale\Credit;
use App\Validators\Sale\CreditValidator;

/**
 * Class CreditRepositoryEloquent.
 *
 * @package namespace App\Repositories\Sale;
 */
class CreditRepositoryEloquent extends BaseRepository implements CreditRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Credit::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
