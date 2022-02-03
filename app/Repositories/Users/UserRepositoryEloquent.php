<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\Users\UserRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories\Users;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    protected $fieldSearchable = [
        'username' => 'like',
        'name' => 'like',
        'email' => 'like',
        'roles.name' => 'like',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }
    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getAdminUsers()
    {
        return $this->whereHas("roles", function($query){
            $query->where("name", "Administrador");
        })->get();
    }



}
