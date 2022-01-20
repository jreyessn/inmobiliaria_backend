<?php

namespace App\Models\Tools;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Tool.
 *
 * @package namespace App\Models\Tools;
 */
class Tool extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name",
        "quantity",
        "user_id"
    ];

    protected $with = [
        "user"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
