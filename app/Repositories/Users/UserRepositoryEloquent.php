<?php

namespace App\Repositories\Users;

use App\Models\TypeProvider;
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

    /* 
    * Obtener usuarios con el rol de Compras 
    */
    public function getUsersPermissionPurchases(){
        $users = $this->model->all();

        $usersWithPermission = $users->reject(function($user){
            return !$user->hasRole('Compras');
        });

        return $usersWithPermission;
    }

    /* 
    * Obtneer usuarios de compras en base al tipo de proveedor
    */
    public function getUsersPurchasesByTypeProvider(string $type_provider){

        $users = $this->model->whereHas('roles', function($query){
            $query->where('role_id', 3);
        })
        ->whereHas('type_provider', function($query) use ($type_provider){
            $query->where('description', $type_provider);
        })
        ->with('type_provider')->get();
        
        return $users;
    }
    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    
}
