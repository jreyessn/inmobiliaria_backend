<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class CategoriesService.
 *
 * @package namespace App\Models\Services;
 */
class CategoriesService extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name"
    ];

    public $timestamps = false;

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
