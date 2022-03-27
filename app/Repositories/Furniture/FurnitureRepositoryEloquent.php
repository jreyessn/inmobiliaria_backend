<?php

namespace App\Repositories\Furniture;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Furniture\FurnitureRepository;
use App\Models\Furniture\Furniture;
use App\Repositories\Customer\CustomerRepositoryEloquent;
use Illuminate\Container\Container as Application;

/**
 * Class FurnitureRepositoryEloquent.
 *
 * @package namespace App\Repositories\Furniture;
 */
class FurnitureRepositoryEloquent extends BaseRepository implements FurnitureRepository
{

    protected $fieldSearchable = [
        "name" => "like",
        "description" => "like",
        "area" => "like",
        "postal_code" => "like",
        "region" => "like",
        "address" => "like",
        "street_number" => "like",
        "aditional_info_address" => "like",
    ];

    private $CustomerRepositoryEloquent;

    function __construct(
        CustomerRepositoryEloquent $CustomerRepositoryEloquent,
        Application $app
    )
    {
        parent::__construct($app);
        $this->CustomerRepositoryEloquent = $CustomerRepositoryEloquent;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Furniture::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Guardar apartamentos
     */
    public function save(array $data)
    {
        $store = $this->create($data);
        
        if($data["customer_name"]){
            $store->customer_id = $this->updateOrCreateCustomer($data)->id;
            $store->save();
        }

        return $store;
    }

    /**
     * Actualizar apartamentos
     */
    public function saveUpdate(array $data, int $id)
    {
        
        $store = $this->find($id);
        $store->fill($data);
        $store->save();
        
        if($data["customer_name"]){
            $store->customer_id = $this->updateOrCreateCustomer($data)->id;
            $store->save();
        }

        return $store;
    }

    public function updateOrCreateCustomer(array $data)
    {
        $storeCustomer = $this->CustomerRepositoryEloquent->updateOrCreate(
            [
                "name" => $data["customer_name"],
                "dni"  => $data["customer_dni"]
            ],
            [
                "name" => $data["customer_name"],
                "dni"  => $data["customer_dni"]
            ]
        );

        return $storeCustomer;
    }
}
