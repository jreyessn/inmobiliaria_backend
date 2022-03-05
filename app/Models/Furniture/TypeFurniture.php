<?php

namespace App\Models\Furniture;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class TypeFurniture.
 *
 * @package namespace App\Models\Furniture;
 */
class TypeFurniture extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $table = "type_furnitures";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name"
    ];

}
