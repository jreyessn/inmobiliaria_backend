<?php

namespace App\Repositories\Contact;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contact\ContactRepository;
use App\Models\Contact\Contact;
use App\Validators\Contact\ContactValidator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

/**
 * Class ContactRepositoryEloquent.
 *
 * @package namespace App\Repositories\Contact;
 */
class ContactRepositoryEloquent extends BaseRepository implements ContactRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Contact::class;
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

        $imageFile = $data['avatar'] ?? null;

        $data['avatar'] = null;

        $store = $this->create($data);
        $store->avatar = $this->saveAvatar($imageFile, $store->id);
        $store->save();

        return $store;
    }

    public function saveUpdate(array $data, int $id)
    {

        $imageFile = $this->saveAvatar($data['avatar'] ?? null, $id);

        if(is_null($imageFile)){
            unset($data['avatar']);
        }
        else{
            $data['avatar'] = $imageFile;
        }

        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }

    private function saveAvatar($imageFile, $id = 0){

        if($imageFile){
            $file =  new File($imageFile);

            return basename(Storage::disk('local')->putFileAs('contact/profile', $file, "{$id}-{$file->hashName()}"));
        }

        return null;
        
    }
}
