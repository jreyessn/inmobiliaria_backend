<?php

namespace App\Repositories\Customer;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Customer\CustomerRepository;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerSubscription;

/**
 * Class CustomerRepositoryEloquent.
 *
 * @package namespace App\Repositories\Customer;
 */
class CustomerRepositoryEloquent extends BaseRepository implements CustomerRepository
{

    protected $fieldSearchable = [
        'name' => 'like',
        'domains' => 'like',
        'description' => 'like',
    ];

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
        $data["coupons"] = 0;

        $store = $this->create($data);

        $this->saveSubscriptions($store, $data["subscriptions"]);

        return $store;
    }
    
    public function saveSubscriptions(Customer $store, array $subscriptions)
    {   
        foreach ($subscriptions as $sub) {
            if(is_null($sub["id"] ?? null)){
                $store->subscriptions()->save(
                    new CustomerSubscription($sub)
                );
            }
            else{
                $subStore = CustomerSubscription::find($sub["id"]);
                $subStore->fill($sub);
                $subStore->save();
            }
        }
    }

    public function emptySubscriptions(Customer $store, $subscriptions_id = [])
    {
        $store->subscriptions()->whereNotIn("id", $subscriptions_id)->delete();
    }

    public function saveUpdate(array $data, int $id)
    {

        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        $subscriptions_id = collect($data["subscriptions"])->map(function($item){
            return $item["id"];
        })->filter(function($id){
            return !is_null($id);
        });

        $this->emptySubscriptions($store, $subscriptions_id);
        $this->saveSubscriptions($store, $data["subscriptions"]);

        return $store;
    }


}
