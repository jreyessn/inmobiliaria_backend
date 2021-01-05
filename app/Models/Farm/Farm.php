<?php

namespace App\Models\Farm;

use App\Models\Person\Person;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Farm.
 *
 * @package namespace App\Models\Farm;
 */
class Farm extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'farm_manager_id',
        'sharecropper_id',
        'direction',
    ];

    protected $hidden = [
        'farm_manager_id',
        'sharecropper_id',
        'deleted_at',
        'updated_at'
    ];

    protected $with = [
        'farm_manager',
        'sharecropper'
    ];


    /**
     * Jefe de Granja
     * 
     * @return void
     */
    public function farm_manager()
    {
        return $this->belongsTo(Person::class, 'farm_manager_id');
    }

    /**
     * Aparecero
     * 
     * @return void
     */
    public function sharecropper()
    {
        return $this->belongsTo(Person::class, 'sharecropper_id');
    }

}
