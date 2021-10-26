<?php

namespace App\Models\Visit;

use App\Models\Audit;
use App\Models\Customer\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Visit.
 *
 * @package namespace App\Models\Visit;
 */
class Visit extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "customer_id",
        "user_id",
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    
    public function user_created()
    {
        return $this->hasOneThrough(User::class, Audit::class, "model_id", "id", "id", "user_id")->where([
            "action"     => "CREAR",
            "model_type" => static::class
        ]);
    }

}
