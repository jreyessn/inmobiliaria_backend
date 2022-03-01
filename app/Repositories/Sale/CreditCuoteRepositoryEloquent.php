<?php

namespace App\Repositories\Sale;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\sale\credit_cuoteRepository;
use App\Models\Sale\CreditCuote;
use App\Validators\Sale\CreditCuoteValidator;

/**
 * Class CreditCuoteRepositoryEloquent.
 *
 * @package namespace App\Repositories\Sale;
 */
class CreditCuoteRepositoryEloquent extends BaseRepository implements CreditCuoteRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CreditCuote::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
