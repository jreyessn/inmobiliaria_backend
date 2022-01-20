<?php

namespace App\Repositories\Equipment;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Equipment\CategoriesEquipmentRepository;
use App\Models\Equipment\CategoriesEquipment;
use App\Validators\Equipment\CategoriesEquipmentValidator;

/**
 * Class CategoriesEquipmentRepositoryEloquent.
 *
 * @package namespace App\Repositories\Equipment;
 */
class CategoriesEquipmentRepositoryEloquent extends BaseRepository implements CategoriesEquipmentRepository
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
        return CategoriesEquipment::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    /**
     * Guardar categorias
     */
    public function save(array $data)
    {
        $store = $this->create($data);

        return $store;
    }

    /**
     * Actualizar categorias
     */
    public function saveUpdate(array $data, int $id)
    {

        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }
    
}
