<?php

namespace App\Models\Coupons;

use App\Models\Customer\Customer;
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
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }
    
    public function user_request()
    {
        return $this->belongsTo(User::class, "user_request_id")->withTrashed();
    }


}
