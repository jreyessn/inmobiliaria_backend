<?php

namespace App\Repositories\Services;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Services\TypesServiceRepository;
use App\Models\Services\TypesService;
use App\Validators\Services\TypesServiceValidator;

/**
 * Class TypesServiceRepositoryEloquent.
 *
 * @package namespace App\Repositories\Services;
 */
class TypesServiceRepositoryEloquent extends BaseRepository implements TypesServiceRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TypesService::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Guardar tipos de servicios
     */
    public function save(array $data)
    {
        $store = $this->create($data);
        $store->spare_parts()->sync($data["spare_parts"] ?? []);

        return $store;
    }

    /**
     * Actualizar tipos de servicios
     */
    public function saveUpdate(array $data, int $id)
    {

        $store = $this->find($id);
        $store->fill($data);
        $store->spare_parts()->sync($data["spare_parts"] ?? []);
        $store->save();

        return $store;
    }
    
}
