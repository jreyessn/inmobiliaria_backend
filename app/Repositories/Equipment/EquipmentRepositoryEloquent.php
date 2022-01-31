<?php

namespace App\Repositories\Equipment;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Equipment\EquipmentRepository;
use App\Models\Equipment\Equipment;
use App\Validators\Equipment\EquipmentValidator;

/**
 * Class EquipmentRepositoryEloquent.
 *
 * @package namespace App\Repositories\Equipment;
 */
class EquipmentRepositoryEloquent extends BaseRepository implements EquipmentRepository
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
        return Equipment::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Guardar equipos
     */
    public function save(array $data)
    {
        $data = sanitize_null($data);

        $store = $this->create($data);
        
        $this->saveParts($store, $data["parts"] ?? []);

        return $store;
    }

    /**
     * Actualizar equipos
     */
    public function saveUpdate(array $data, int $id)
    {

        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        $this->saveParts($store, $data["parts"] ?? []);

        return $store;
    }
    
    /**
     * Guarda/Actualiza las partes del equipo
     * 
     * @param object $model Instancia del modelo
     * @param array $data Array multidimensional de las partes
     */
    private function saveParts($model, array $data)
    {    

        $excludeIds = collect($data)->map(function($item){ return $item["id"] ?? 0; });

        $model->parts()->whereNotIn("id", $excludeIds)->delete();

        foreach ($data as $item) {

            $part = $model->parts()->find($item["id"] ?? 0);

            $item["equipment_id"] = $model->id;
            $item = sanitize_null($item);
            
            if($part){
                $part->fill($item);
                $part->save();
            }
            else{
                $model->parts()->create($item);
            }

        }
        
    }

}
