<?php

namespace App\Repositories\BranchOffices;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\BranchOffices\BranchOfficeRepository;
use App\Models\BranchOffices\BranchOffice;
use App\Validators\BranchOffices\BranchOfficeValidator;

/**
 * Class BranchOfficeRepositoryEloquent.
 *
 * @package namespace App\Repositories\BranchOffices;
 */
class BranchOfficeRepositoryEloquent extends BaseRepository implements BranchOfficeRepository
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
        return BranchOffice::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
     
    /**
     * Guardar sucursales
     */
    public function save(array $data)
    {
        $store = $this->create($data);

        return $store;
    }

    /**
     * Actualizar sucursales
     */
    public function saveUpdate(array $data, int $id)
    {        
        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }
    
}
