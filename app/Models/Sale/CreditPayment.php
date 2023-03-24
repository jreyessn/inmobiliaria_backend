<?php

namespace App\Models\Sale;

use App\Models\BranchOffices\BranchOffice;
use App\Models\BranchOffices\ModelHasBranchOffice;
use App\Models\Currency\Currency;
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
        "currency_id",
        "rate",
        "credit_cuote_id",
        "payment_method_id",
        "note",
        "nfc",
        "interest_percentage"
    ];

    protected $with = [
        "payment_method",
        "currency"
    ];

    protected $appends = [
        "remaining_balance",
        "total" 
    ];

    protected $casts = [
        "amount"              => "float",
        "interest_percentage" => "float",
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class)->withTrashed();
    }

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

    public function getTotalAttribute(){
        return $this->amount * (($this->interest_percentage / 100) + 1);
    }

    public function branch_offices()
    {
        return $this->hasManyThrough(BranchOffice::class, ModelHasBranchOffice::class, "model_id", "id", "id", "branch_office_id")->where([
            "model_type" => static::class
        ]);
    }
}
