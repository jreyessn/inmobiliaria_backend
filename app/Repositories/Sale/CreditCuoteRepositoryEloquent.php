<?php

namespace App\Repositories\Sale;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\sale\credit_cuoteRepository;
use App\Models\Sale\CreditCuote;
use App\Validators\Sale\CreditCuoteValidator;

/**
 * Class CreditCuoteRepositoryEloquent.
 *
 * @package namespace App\Repositories\Sale;
 */
class CreditCuoteRepositoryEloquent extends BaseRepository implements CreditCuoteRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CreditCuote::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Almacena cada cuota en base al crédito
     * 
     * @param App\Models\Sale\Credit $credit Modelo de credito
     * @param array $cuotes[number_letter] Letra
     * @param array $cuotes[giro_at] Fecha de Giro
     * @param array $cuotes[expiration_at] Fecha de Expiración
     */
    public function save($credit, $cuotes){
        foreach ($cuotes as $cuote) {
            $this->createOneCuote($credit, $cuote, count($cuotes));
        }
    }

    /**
     * Crea una cuota
     */
    public function createOneCuote($credit, $cuote, $countCuotes = 1){
        $cuote["credit_id"] = $credit->id;
        $cuote["total"]     = $credit->total / $countCuotes; 

        return $this->create($cuote);
    }
    
}
