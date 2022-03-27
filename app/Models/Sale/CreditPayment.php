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

    protected $with = [
        "payment_method",
    ];

    protected $appends = [
        "remaining_balance"   
    ];

    protected $casts = [
        "amount" => "float"
    ];

    public function credit_cuote()
    {
        return $this->belongsTo(CreditCuote::class);
    }
    
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class)->withTrashed();
    }

    public function getRemainingBalanceAttribute()
    {
        $paymentsPrevious = CreditPayment::where("credit_cuote_id", $this->credit_cuote_id)
                                          ->where("id", "<", $this->id)
                                          ->sum("amount");
        $totalCredit      =  $this->credit_cuote()->select("total")->first()->total;

        return $totalCredit - $paymentsPrevious - $this->amount;
    }
}
