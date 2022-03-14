<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Models\User\UserPreferences;
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

    /**
     * get admins
     */
    public function getAdminUsers()
    {
        return $this->whereHas("roles", function($query){
            $query->where("name", "Administrador");
        })->get();
    }

    /**
     * update preferences
     * 
     * @param $data Data
     * @param $user_id ID user
     */
    public function updatePreferences(array $data, $user_id){
        $data = collect($data);
        $keys = defaultPreferences();

        foreach (array_keys($keys) as $key) {
            $params = [
                "key"     => $key,
                "user_id" => $user_id
            ];

            $userPreference = UserPreferences::where($params)->first();

            if($userPreference && $data->has($key)){
                $userPreference->value = $data->get($key);
                $userPreference->save();
            }
            else if($data->has($key)){
                $params["value"] = $data->get($key);
                UserPreferences::create($params);
            }
        }

    }


}
