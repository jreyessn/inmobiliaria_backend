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
        $cuotesRegistered = $this->where("credit_id", $credit->id)->get();

        // Si anteriormente habian 3 cuotas y ahora son 2, entonces se elimina la restante
        $cuotesRegistered->slice(count($cuotes))->each(function($cuoteModel){
            $cuoteModel->forceDelete();
        });

        foreach ($cuotes as $key => $cuote) {
            $cuoteRegistered = $cuotesRegistered[$key] ?? null;

            // Actualizar las cuotas registradas
            if($cuoteRegistered){
                $cuoteRegistered->total = $credit->total / count($cuotes);
                $cuoteRegistered->number_letter = $cuote["number_letter"];
                $cuoteRegistered->save();
            }
            else{
                $this->createOneCuote($credit, $cuote, count($cuotes));
            }
        }

    }

    /**
     * Crea una cuota
     */
    public function createOneCuote($credit, $cuote, $countCuotes = 1){
        $cuote["credit_id"] = $credit->id;
        $cuote["total"]     = $credit->total / $countCuotes; 
        
        return CreditCuote::create($cuote);
    }
    
}
