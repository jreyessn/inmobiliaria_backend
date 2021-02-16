<?php

namespace App\Repositories\Customer;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Customer\CustomerRepository;
use App\Models\Customer\Customer;
use App\Validators\Customer\CustomerValidator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

/**
 * Class CustomerRepositoryEloquent.
 *
 * @package namespace App\Repositories\Customer;
 */
class CustomerRepositoryEloquent extends BaseRepository implements CustomerRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Customer::class;
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

        $imageFile = $data['image'] ?? '';

        $data['image'] = null;

        $store = $this->create($data);
        $store->image = $this->saveImage($imageFile, $store->id);
        $store->save();

        return $store;
    }

    public function saveUpdate(array $data, int $id)
    {

        $imageFile = $this->saveImage($data['image'] ?? null, $id);

        if(is_null($imageFile)){
            unset($data['image']);
        }

        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }

    private function saveImage($imageFile, $id = 0){

        if($imageFile){
            $file =  new File($imageFile);

            return basename(Storage::disk('local')->putFileAs('customer/profile', $file, "{$id}-{$file->hashName()}"));
        }

        return null;
        
    }
    
}
