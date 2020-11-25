<?php

namespace App\Repositories\ApplicantProvider;

use App\Models\ApplicantProvider;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ApplicantProvider\ApplicantProviderRepository;
use Illuminate\Http\File;

/**
 * Class ApplicantProviderRepositoryEloquent.
 *
 * @package namespace App\Repositories\ApplicantProvider;
 */
class ApplicantProviderRepositoryEloquent extends BaseRepository implements ApplicantProviderRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ApplicantProvider::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    public function customPaginate()
    {
        $perPage = (int) request()->get('perPage', config('repository.pagination.limit', 10));

        return $this->paginate($perPage);
    }

    public function save($data)
    {
        $store = $this->create($data);
        $file =  new File($data['authorization_file']);

        $name =  basename(Storage::disk('local')->putFileAs('applicant_providers_authorizations', $file, "{$store->id}-{$file->hashName()}"));

        $store->authorization_file = $name;
        $store->save();

        return $store;        
    }

}
