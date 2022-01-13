<?php

namespace App\Repositories\Equipment;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Equipment\BrandsEquipmentRepository;
use App\Models\Equipment\BrandsEquipment;
use App\Validators\Equipment\BrandsEquipmentValidator;

/**
 * Class BrandsEquipmentRepositoryEloquent.
 *
 * @package namespace App\Repositories\Equipment;
 */
class BrandsEquipmentRepositoryEloquent extends BaseRepository implements BrandsEquipmentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return BrandsEquipment::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Guardar marcas
     */
    public function save(array $data)
    {
        $store = $this->create($data);

        return $store;
    }

    /**
     * Actualizar marcas
     */
    public function saveUpdate(array $data, int $id)
    {

        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }
    
}
