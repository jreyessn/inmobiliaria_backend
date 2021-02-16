<?php

namespace App\Repositories\System;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\System\SystemRepository;
use App\Models\System\System;
use App\Models\System\SystemCredential;
use App\Validators\System\SystemValidator;

/**
 * Class SystemRepositoryEloquent.
 *
 * @package namespace App\Repositories\System;
 */
class SystemRepositoryEloquent extends BaseRepository implements SystemRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return System::class;
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
        
        $this->saveCredentials($store, $data);

        return $store;
    }

    public function saveUpdate(array $data, int $id)
    {

        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        $this->saveCredentials($store, $data);

        return $store;
    }

    private function saveCredentials(System $store, array $data)
    {
        $merged = array_merge($data['credentials_users'], $data['credentials_servers']);
        $merged = array_map(function($item){
            return new SystemCredential($item);
        }, $merged);
        
        $store->credentials()->delete();
        $store->credentials()->saveMany($merged);
    }
    
}
