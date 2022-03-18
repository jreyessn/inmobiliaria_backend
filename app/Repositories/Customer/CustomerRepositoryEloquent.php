<?php

namespace App\Repositories\Customer;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Customer\CustomerRepository;
use App\Models\Customer\Customer;
use App\Validators\Customer\CustomerValidator;

/**
 * Class CustomerRepositoryEloquent.
 *
 * @package namespace App\Repositories\Customer;
 */
class CustomerRepositoryEloquent extends BaseRepository implements CustomerRepository
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
        return Customer::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Guardar clientes
     */
    public function save(array $data)
    {
        $data  = sanitize_null($data);
        $store = $this->create($data);

        return $store;
    }

    /**
     * Actualizar clientes
     */
    public function saveUpdate(array $data, int $id)
    {     
        $data  = sanitize_null($data);   
        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }
    
}
