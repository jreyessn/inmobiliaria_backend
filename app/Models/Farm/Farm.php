<?php

namespace App\Models\Farm;

use App\Models\Person\Person;
use App\Models\User;
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
        "centro",
        "supervisor",
        "gerente",
        "nombre_centro",
        "nombre_supervisor",
        "nombre_gerente",
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];


    public function user()
    {
        return $this->belongsToMany(User::class, 'farms_users', 'farm_id', 'user_id');
    }


}
