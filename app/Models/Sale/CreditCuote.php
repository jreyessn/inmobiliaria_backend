<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class CreditCuote.
 *
 * @package namespace App\Models\Sale;
 */
class CreditCuote extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "credit_id",
        "number_letter",
        "expiration_at",
        "total",
    ];

    public function credit(){
        return $this->belongsTo(Credit::class);
    }

}
