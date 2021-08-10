<?php

namespace App\Models\Coupons;

use App\Models\Audit;
use App\Models\Customer\Customer;
use App\Models\User;
use App\Observers\CouponsQuantityCustomerObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class CouponsMovements.
 *
 * @package namespace App\Models\Coupons;
 */
class CouponsMovements extends Model implements Transformable
{

    protected $table = "coupons_movements";

    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "customer_id",
        "type_movement",
        "quantity",
        "price",
        "is_automatic",
        "comment",
    ];

    protected $casts = [
        "quantity" => "integer",
        "price"    => "float"
    ];

    protected $appends = [
        "total"
    ];

    protected $with = [
        "user_created"
    ];

    protected static function boot()
    {
        parent::boot();

        CouponsMovements::observe(CouponsQuantityCustomerObserver::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getTotalAttribute()
    {
        return decimal($this->price * $this->quantity);
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
