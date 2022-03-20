<?php

namespace App\Models\Sale;

use App\Models\Audit;
use App\Models\Customer\Customer;
use App\Models\Furniture\Furniture;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Sale.
 *
 * @package namespace App\Models\Sale;
 */
class Sale extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "serie",
        "number",
        "furniture_id",
        "document_id",
        "customer_id",
        "payment_method_id",
        "tax_percentage",
        "subtotal",
        "total",
        "note",
        "is_credit",
        "status",
    ];

    protected $casts = [
        "total"     => "float",
        "is_credit" => "integer"
    ];

    public function furniture()
    {
        return $this->belongsTo(Furniture::class)->withTrashed();
    }

    public function document()
    {
        return $this->belongsTo(Document::class)->withTrashed();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class)->withTrashed();
    }

    public function credit()
    {
        return $this->hasOne(Credit::class);
    }

    protected static function booted()
    {
        static::deleted(function ($model) {
            $model->credit->cuotes()->delete();
            $model->credit->delete();
        });
    }

    /**
     * Obtener el usuario que ha creado el registro por medio de la tabla de auditoria.
     */
    public function user_created()
    {
        return $this->hasOneThrough(User::class, Audit::class, "model_id", "id", "id", "user_id")->where([
            "action"     => "CREAR",
            "model_type" => static::class
        ]);
    }
}
