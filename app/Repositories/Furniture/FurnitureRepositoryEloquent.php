<?php

namespace App\Repositories\Furniture;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Furniture\FurnitureRepository;
use App\Models\Furniture\Furniture;
use App\Validators\Furniture\FurnitureValidator;

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
        "unit_price" => "like",
        "price_sale" => "like",
        "postal_code" => "like",
        "region" => "like",
        "address" => "like",
        "street_number" => "like",
        "aditional_info_address" => "like",
    ];

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

        return $store;
    }
}
