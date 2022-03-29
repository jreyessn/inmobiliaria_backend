<?php

namespace App\Repositories\Sale;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Sale\CreditRepository;
use App\Models\Sale\Credit;
use Illuminate\Container\Container as Application;

/**
 * Class CreditRepositoryEloquent.
 *
 * @package namespace App\Repositories\Sale;
 */
class CreditRepositoryEloquent extends BaseRepository implements CreditRepository
{

    protected $fieldSearchable = [
        "furniture.name" => "like",
        "furniture.type_furniture.name" => "like",
        "furniture.customer.name" => "like",
        "furniture.unit_price" => "like",
        "total" => "like",
    ];
    
    private $CreditCuoteRepositoryEloquent;

    function __construct(
        CreditCuoteRepositoryEloquent $CreditCuoteRepositoryEloquent,
        Application $app
    )
    {
        parent::__construct($app);
        $this->CreditCuoteRepositoryEloquent = $CreditCuoteRepositoryEloquent;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Credit::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Generar créditos para la venta
     * 
     * @param App\Models\Furniture\Furniture $furniture Modelo de Inmueble
     * @param array $body[credit_amount_anticipated] Monto Anticipado
     * @param array $body[credit_interest_percentage] Porcentaje de Interés
     * @param array $body[credit_cuotes] Cuotas
     */
    public function save($furniture, $body){
        
        // Se calcula total sin el monto anticipado
        $total_out_anticipated = $furniture->initial_price - (float) $body["credit_amount_anticipated"];

        $store = $this->create([
            "furniture_id"       => $furniture->id,
            "total"               => sum_amount_tax($total_out_anticipated, $body["credit_interest_percentage"]),
            "amount_anticipated"  => $body["credit_amount_anticipated"],
            "interest_percentage" => $body["credit_interest_percentage"],
        ]);

        $this->CreditCuoteRepositoryEloquent->save($store, $body["credit_cuotes"]);

        return $store;
    }
    
}
