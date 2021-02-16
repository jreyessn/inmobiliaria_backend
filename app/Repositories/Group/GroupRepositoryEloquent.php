<?php

namespace App\Repositories\Group;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Group\GroupRepository;
use App\Models\Group\Group;
use App\Validators\Group\GroupValidator;

/**
 * Class GroupRepositoryEloquent.
 *
 * @package namespace App\Repositories\Group;
 */
class GroupRepositoryEloquent extends BaseRepository implements GroupRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Group::class;
    }

    
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    public function save(array $data)
    {
        $store = $this->create($data);
        $store->users()->sync($data['users']);
        
        return $store;
    }

    public function saveUpdate(array $data, int $id)
    {

        $store = $this->find($id);
        $store->fill($data);
        $store->save();
        $store->users()->sync($data['users']);

        return $store;
    }
}
