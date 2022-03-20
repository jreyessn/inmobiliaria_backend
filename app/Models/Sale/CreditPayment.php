<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class CreditPayment.
 *
 * @package namespace App\Models\Sale;
 */
class CreditPayment extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "amount",
        "credit_cuote_id",
        "payment_method_id",
        "note",
    ];

    public function credit_cuote()
    {
        return $this->belongsTo(CreditCuote::class);
    }
    
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class)->withTrashed();
    }
}
