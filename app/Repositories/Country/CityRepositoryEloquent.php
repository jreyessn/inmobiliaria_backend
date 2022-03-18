<?php

namespace App\Repositories\Country;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Country\CityRepository;
use App\Models\Country\City;
use App\Validators\Country\CityValidator;

/**
 * Class CityRepositoryEloquent.
 *
 * @package namespace App\Repositories\Country;
 */
class CityRepositoryEloquent extends BaseRepository implements CityRepository
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
        return City::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
     
    /**
     * Guardar ciudades
     */
    public function save(array $data)
    {

        $data["country_id"] = config("app.country_id") ?? 1;
        $store = $this->create($data);

        return $store;
    }

    /**
     * Actualizar ciudades
     */
    public function saveUpdate(array $data, int $id)
    {
        $data["country_id"] = config("app.country_id") ?? 1;
        
        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }
}
