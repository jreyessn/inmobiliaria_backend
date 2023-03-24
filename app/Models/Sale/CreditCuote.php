<?php

namespace App\Models\Sale;

use App\Models\BranchOffices\BranchOffice;
use App\Models\BranchOffices\ModelHasBranchOffice;
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
        "days_late",
        "is_expired",
        "amount", // Utilizado por el componente de modal de créditos
        "periods_days" // Utilizado por el componente de modal de créditos
    ];

    protected $casts = [
        "giro_at"       => "datetime",
        "expiration_at" => "datetime",
    ];

    public function credit(){
        return $this->belongsTo(Credit::class);
    }

    public function payments(){
        return $this->hasMany(CreditPayment::class)->orderBy("created_at", "desc");
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
        if(now()->gt($this->expiration_at) && $this->status == 0){
            return now()->diff($this->expiration_at, "days")->days;
        }

        if($this->status == 1){
            $last_payment = $this->payments()->orderBy("created_at", "desc")->first();
            return $last_payment->created_at->gt($this->expiration_at . " 23:59:59")? 
                    $last_payment->created_at->diff($this->expiration_at, "days")->days : 
                    0;
        }

        return 0;
    }

    public function getIsExpiredAttribute(){
        return now()->gt($this->expiration_at) && $this->status == 0? true: false;
    }

    public function getAmountAttribute(){
        return $this->total;
    }

    public function getNumberLetterAttribute($text){
        return $text == "Contado"? $text : "Letra " . $text;
    }

    public function getPeriodsDaysAttribute(){
        return $this->expiration_at->diff($this->giro_at, "days")->days ?? 1;
    }

    public function branch_offices()
    {
        return $this->hasManyThrough(BranchOffice::class, ModelHasBranchOffice::class, "model_id", "id", "id", "branch_office_id")->where([
            "model_type" => static::class
        ]);
    }

}
