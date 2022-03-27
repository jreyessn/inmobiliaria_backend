<?php

namespace App\Models\Customer;

use App\Models\Furniture\Furniture;
use App\Models\Sale\Credit;
use Illuminate\Database\Eloquent\Model;
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
        "name",
        "email",
        "phone",
        "dni"
    ];

    public function credits()
    {
        return $this->hasManyThrough(Credit::class, Furniture::class);
    }

}
