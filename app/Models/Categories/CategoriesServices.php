<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class CategoriesServices.
 *
 * @package namespace App\Models\Categories;
 */
class CategoriesServices extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = "categories_services";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name"
    ];

    public $timestamps = false;

}
