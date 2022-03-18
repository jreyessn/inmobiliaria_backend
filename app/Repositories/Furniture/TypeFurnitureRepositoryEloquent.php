<?php

namespace App\Repositories\Furniture;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\furniture\type_furnitureRepository;
use App\Models\Furniture\TypeFurniture;
use App\Validators\Furniture\TypeFurnitureValidator;

/**
 * Class TypeFurnitureRepositoryEloquent.
 *
 * @package namespace App\Repositories\Furniture;
 */
class TypeFurnitureRepositoryEloquent extends BaseRepository implements TypeFurnitureRepository
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
        return TypeFurniture::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
