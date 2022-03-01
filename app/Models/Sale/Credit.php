<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Credit.
 *
 * @package namespace App\Models\Sale;
 */
class Credit extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "sale_id",
        "total",
        "amount_anticipated",
        "interest_percentage",
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

}
