<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\AppRepository;
use App\Repositories\Users\UserRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories\Users;
 */
class UserRepositoryEloquent extends AppRepository implements UserRepository
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

    public function getUsersPermissionPurchases(){
        $users = $this->model->all();

        $usersWithPermission = $users->reject(function($user){
            return !$user->hasRole('Compras');
        });

        return $usersWithPermission;
    }
    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    
}
