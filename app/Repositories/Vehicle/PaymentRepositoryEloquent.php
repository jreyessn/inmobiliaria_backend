<?php

namespace App\Repositories\Vehicle;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Vehicle\PaymentRepository;
use App\Models\Vehicle\Payment;
use App\Validators\Vehicle\PaymentValidator;

/**
 * Class PaymentRepositoryEloquent.
 *
 * @package namespace App\Repositories\Vehicle;
 */
class PaymentRepositoryEloquent extends BaseRepository implements PaymentRepository
{

    protected $fieldSearchable = [
        "vehicle.name" => "like",
        "concept"      => "like",
        "km_current"   => "like",
        "amount"       => "like",
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Payment::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    
    /**
     * Guardar pagos
     */
    public function save(array $data)
    {
        $store = $this->create($data);
        
        return $store;
    }

    /**
     * Actualizar pagos
     */
    public function saveUpdate(array $data, int $id)
    {
        $store = $this->find($id);
        $store->fill($data);
        $store->save();

        return $store;
    }
    
}
