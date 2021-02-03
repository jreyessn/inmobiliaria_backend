<?php

namespace App\Repositories\Person;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Person\PersonRepository;
use App\Models\Person\Person;
use App\Validators\Person\PersonValidator;

/**
 * Class PersonRepositoryEloquent.
 *
 * @package namespace App\Repositories\Person;
 */
class PersonRepositoryEloquent extends BaseRepository implements PersonRepository
{

    protected $fieldSearchable = [
        'first_name' => 'like',
        'last_name' => 'like',
        'email' => 'like',
        'phone' => 'like',
        'occupation' => 'like',
        'street' => 'like',
        'city' => 'like',
        'country' => 'like',
        'postcode' => 'like',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Person::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
