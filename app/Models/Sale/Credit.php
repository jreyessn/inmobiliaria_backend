<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Credit.
 *
 * @package namespace App\Models\Sale;
 */
class Credit extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "sale_id",
        "total",
        "amount_anticipated",
        "interest_percentage",
    ];

    protected $appends = [
        "amount_payment",
        "amount_pending",
        "status",
        "status_text",
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function cuotes()
    {
        return $this->hasMany(CreditCuote::class);
    }
    
    public function getAmountPaymentAttribute(){
        $sumPayments = $this->hasManyThrough(CreditPayment::class, CreditCuote::class)->get()->sum("amount");

        return $sumPayments;
    }

    public function getAmountPendingAttribute(){
        return $this->total - $this->amount_payment;
    }

    public function getStatusAttribute(){
        return $this->amount_pending == 0? 1 : 0;
    }

    public function getStatusTextAttribute(){
        return $this->status == 1? "Pagado" : "Pendiente";
    }


}
