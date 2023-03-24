<?php

namespace App\Models\Customer;

use App\Models\BranchOffices\BranchOffice;
use App\Models\BranchOffices\ModelHasBranchOffice;
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

    public function branch_offices()
    {
        return $this->hasManyThrough(BranchOffice::class, ModelHasBranchOffice::class, "model_id", "id", "id", "branch_office_id")->where([
            "model_type" => static::class
        ]);
    }

}
