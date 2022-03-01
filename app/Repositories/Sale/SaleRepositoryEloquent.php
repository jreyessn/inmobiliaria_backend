<?php

namespace App\Repositories\Sale;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\sale\saleRepository;
use App\Models\Sale\Sale;
use App\Validators\Sale\SaleValidator;

/**
 * Class SaleRepositoryEloquent.
 *
 * @package namespace App\Repositories\Sale;
 */
class SaleRepositoryEloquent extends BaseRepository implements SaleRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Sale::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
