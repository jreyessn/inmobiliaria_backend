<?php

namespace App\Models\Sale;

use App\Models\Currency\Currency;
use App\Models\Furniture\Furniture;
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
        "furniture_id",
        "total",
        "currency_id",
        "rate",
        "status",
        "amount_anticipated",
        "interest_percentage",
    ];

    protected $appends = [
        "amount_payment",
        "amount_pending",
        "status_text",
        "tax_amount"
    ];

    protected $with = [
        "currency"
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class)->withTrashed();
    }

    public function furniture()
    {
        return $this->belongsTo(Furniture::class);
    }

    public function cuotes()
    {
        return $this->hasMany(CreditCuote::class)->orderBy("expiration_at", "ASC");
    }

    public function payments()
    {
        return $this->hasManyThrough(CreditPayment::class, CreditCuote::class);
    }
    
    public function getAmountPaymentAttribute(){
        $sumPayments = $this->payments()->get()->sum("amount");

        return $sumPayments;
    }

    public function getTaxAmountAttribute(){
        return $this->interest_percentage > 0? ($this->interest_percentage / 100) * $this->total : 0;
    }

    public function getAmountPendingAttribute(){
        return $this->total - $this->amount_payment;
    }

    public function getStatusTextAttribute(){
        return $this->status == 1? "Pagado" : "Pendiente";
    }

}
