<?php

namespace App\Models\Customer;

use App\Models\Coupons\CouponsMovements;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Customer.
 *
 * @package namespace App\Models\Customer;
 */
class Customer extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tradename',
        'business_name',
        'coupons',
        'price_coupon',
        'street',
        'street_number',
        'colony',
        'phone',
        'email',
    ];
    
    protected $appends = [
        "coupons_used",
        "coupons_bought",
        "coupons_returned",
    ];

    public function subscriptions()
    {
        return $this->hasMany(CustomerSubscription::class);
    }

    public function movements()
    {
        return $this->hasMany(CouponsMovements::class);
    }

    public function getCouponsUsedAttribute()
    {
        return $this->movements()->where("type_movement", getMovement(3))->sum("quantity");
    }

    public function getCouponsBoughtAttribute()
    {
        return $this->movements()->where("type_movement", getMovement(1))->sum("quantity");
    }

    public function getCouponsReturnedAttribute()
    {
        return $this->movements()->where("type_movement", getMovement(1))->sum("quantity");
    }

}
