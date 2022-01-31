<?php

namespace App\Repositories\Services;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Services\ServiceRepository;
use App\Models\Services\Service;
use App\Validators\Services\ServiceValidator;

/**
 * Class ServiceRepositoryEloquent.
 *
 * @package namespace App\Repositories\Services;
 */
class ServiceRepositoryEloquent extends BaseRepository implements ServiceRepository
{
    protected $fieldSearchable = [
        "type_service.name" => "like",
        "categories_service.name" => "like",
        "equipments_part.name" => "like",
        "equipment.name" => "like",
        "farm.name" => "like",
        "user_assigned.name" => "like"
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Service::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Paginación personalizada. Se agregan propiedades para que sean posteriormente
     * utilizadas en ordenación, transformación, criterios, etc.
     */
    public function customPaginate()
    {
        $perPage = request()->get('perPage', config('repository.pagination.limit'));

        return $this->scopeQuery(function($query){
            $query->selectRaw("
                *, 
                (if(now() > date_format(event_date, '%Y-%m-%d 23:59:59') and status = 0, 2, status)) as status
            ");
            return $query;
        })->paginate($perPage);
    }

    /**
     * Guardar servicios
     */
    public function save(array $data)
    {
        $store = $this->create($data);
        
        return $store;
    }

    /**
     * Actualizar servicios
     */
    public function saveUpdate(array $data, int $id)
    {
        $store = $this->find($id);
        $store->fill($data);

        if(($data["status"] ?? 0) == 1){
            $store->completed_at = now();
        }

        $store->save();

        return $store;
    }
    
    
}
