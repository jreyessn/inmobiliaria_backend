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
        "reference",
        "giro_at",
        "expiration_at",
        "total",
    ];

    protected $appends = [
        "amount_pending",
        "status",
        "status_text",
        "days_late"
    ];

    protected $casts = [
        "expiration_at" => "datetime"
    ];

    public function credit(){
        return $this->belongsTo(Credit::class);
    }

    public function payments(){
        return $this->hasMany(CreditPayment::class);
    }

    public function getAmountPendingAttribute(){
        $paymentsSum = $this->payments()->get()->sum("amount");

        return $this->total - $paymentsSum;
    }

    public function getStatusAttribute(){
        return $this->amount_pending == 0? 1 : 0;
    }

    public function getStatusTextAttribute(){
        return $this->status == 1? "Pagado" : "Pendiente";
    }

    public function getDaysLateAttribute(){
        if(now()->gt($this->expiration_at)){
            return $this->expiration_at->diffInDays();
        }

        return 0;
    }

}
