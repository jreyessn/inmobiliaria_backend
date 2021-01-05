<?php

namespace App\Repositories\Farm;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Farm\FarmRepository;
use App\Models\Farm\Farm;
use App\Models\Person\Person;
use App\Validators\Farm\FarmValidator;

/**
 * Class FarmRepositoryEloquent.
 *
 * @package namespace App\Repositories\Farm;
 */
class FarmRepositoryEloquent extends BaseRepository implements FarmRepository
{
    protected $fieldSearchable = [
        'name' => 'like',
        'direction' => 'like',
        'farm_manager.name' => 'like',
        'sharecropper.name' => 'like',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Farm::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Guardar los datos de la granja, jefe y aparceros
     * 
     * @param array $data
     * @return App\Models\Farm\Farm
     */
    public function save(array $data): Farm
    {
        $data['farm_manager_id'] = Person::create([
            'name' => $data['farm_manager'],
            'farm_occupation' => 'Jefe de Granja'
        ])->id;
        
        $data['sharecropper_id'] = Person::create([
            'name' => $data['sharecropper'],
            'farm_occupation' => 'Aparcero'
        ])->id;

        $farm = $this->create($data);

        return $farm;
    }

    /**
     * Actualizar los datos de la granja, jefe y aparceros
     * 
     * @param array $data
     * @return App\Models\Farm\Farm
     */
    public function saveUpdate(array $data, int $id): Farm
    {
        $farm = $this->find($id);
        $farm->farm_manager->update(['name' => $data['farm_manager']]);
        $farm->sharecropper->update(['name' => $data['sharecropper']]);
        $farm->fill($data);
        $farm->save();
        
        return $farm;
    }
    
}
