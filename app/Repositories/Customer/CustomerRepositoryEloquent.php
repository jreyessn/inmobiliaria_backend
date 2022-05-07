<?php

namespace App\Repositories\Customer;

use App\Criteria\SinceUntilCreatedAtCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Customer\CustomerRepository;
use App\Models\Customer\Customer;
use App\Repositories\Sale\CreditPaymentRepositoryEloquent;
use App\Validators\Customer\CustomerValidator;
use Illuminate\Container\Container as Application;

/**
 * Class CustomerRepositoryEloquent.
 *
 * @package namespace App\Repositories\Customer;
 */
class CustomerRepositoryEloquent extends BaseRepository implements CustomerRepository
{

    protected $fieldSearchable = [
        "name" => "like"
    ];
    
    private $CreditPaymentRepositoryEloquent;

    function __construct(
        CreditPaymentRepositoryEloquent $CreditPaymentRepositoryEloquent,
        Application $app
    )
    {
        parent::__construct($app);
        $this->CreditPaymentRepositoryEloquent = $CreditPaymentRepositoryEloquent;
    }


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

    /**
     * Paginacion estados de cuenta
     */
    public function paginateAccountStatus($perPage) {
        $paginate = $this->paginate($perPage);

        foreach ($paginate->items() as $item) {
            $item = $this->appendAccountStatus($item);
        }

        return $paginate;
    }

    /**
     * Obtener todo estados de cuenta
     */
    public function getAccountStatus() {
        $items = $this->get();

        foreach ($items as $item) {
            $item = $this->appendAccountStatus($item);
        }

        return $items;
    }

    /**
     * Mapea toda la información referente al estado de cuenta del cliente
     */
    public function appendAccountStatus(Customer $customer)
    {
        $this->CreditPaymentRepositoryEloquent->pushCriteria(SinceUntilCreatedAtCriteria::class);

        $customer->credits->load(["furniture"]);        
        $customer->total              = $customer->credits->sum("total");
        $customer->total_paid         = $customer->credits->sum("amount_payment");
        $customer->total_balance      = $customer->total - $customer->total_paid;
        
        foreach ($customer->credits as $credit) {
            $credit->payments = $this->CreditPaymentRepositoryEloquent
                                     ->whereHas("credit_cuote", function($query) use ($credit){
                                           $query->where("credit_id", $credit->id);
                                      })
                                      ->with("credit_cuote")
                                      ->orderBy("id")
                                      ->get();
        }

        return $customer;
    }

    /**
     * Guardar clientes
     */
    public function save(array $data)
    {
        $data  = sanitize_null($data);
        $store = $this->create($data);

        return $store;
    }

    /**
     * Actualizar clientes
     */
    public function saveUpdate(array $data, int $id)
    {     
        $data  = sanitize_null($data);   
        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }
    
}
