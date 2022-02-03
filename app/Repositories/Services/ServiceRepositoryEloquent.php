<?php

namespace App\Repositories\Services;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Services\ServiceRepository;
use App\Models\Services\Service;
use App\Notifications\Services\AssignedService;
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
        
        $userSession = request()->user();

        // existe el usuario asignado
        // existe el usuario en sesion (ya que al menos una vez se ejecuta por cronjob)
        // el usuario asignado es diferente al de la sesion
        if($store->user_assigned && $userSession && $store->user_assigned_id != $userSession->id){
           $this->notifyTechnical($store);
        }

        return $store;
    }

    /**
     * Actualizar servicios
     */
    public function saveUpdate(array $data, int $id)
    {
        $store = $this->find($id);
        $userOrigianlId = $store->user_assigned->id ?? null;
        $userSession = request()->user();

        $store->fill($data);

        if(($data["status"] ?? 0) == 1){
            $store->completed_at = now();
        }

        $store->save();

        $service = $this->find($id);


        // existe el usuario asignado
        // el usuario asignado es diferente al de la sesion
        // el usuario asignado es diferente al que habia anteriormente
        if(
            $service->user_assigned && 
            $service->user_assigned_id != $userSession->id && 
            $service->user_assigned_id != $userOrigianlId){
            $this->notifyTechnical($service);
         }

        return $service;
    }

    /**
     * Notificar al técnico asignado
     * 
     * @param $service Servicio
     */
    private function notifyTechnical($service){
        $values = [
            "id"                    => $service->id,
            "service_category"      => $service->categories_service->name,
            "event_date"            => $service->event_date->format("d/m/Y"),
            "equipment_name"        => $service->equipment->name,
            "equipment_part_name"   => $service->equipments_part->name ?? 'No aplica',
            "type_service_name"     => $service->type_service->name ?? '',
        ];

        $service->user_assigned->notify(new AssignedService($values));
    }
    
    
}
