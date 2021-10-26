<?php

namespace App\Models\Coupons;

use App\Models\Customer\Customer;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Observers\CouponMovementObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class CouponsRequest.
 *
 * @package namespace App\Models\Coupons;
 */
class CouponsRequest extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "customer_id",
        "quantity_coupons",
        "user_request_id",
        "approved",
        "observation",
        "approved_at",
        "payment_method_id",
        "comment",
    ];

    protected $with = [
        "customer",
        "user_request"
    ];

    protected $appends = [
        "folio"
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class)->withTrashed();
    }
    
    public function user_request()
    {
        return $this->belongsTo(User::class, "user_request_id")->withTrashed();
    }

    public function getFolioAttribute()
    {
        return format_ceros($this->id, 5);
    }
}
