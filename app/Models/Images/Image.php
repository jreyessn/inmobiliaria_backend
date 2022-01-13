<?php

namespace App\Models\Images;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Image.
 *
 * @package namespace App\Models\Images;
 */
class Image extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "model_type",
        "model_id",
        "type",
        "name",
        "original_name",
    ];

    protected $appends = [
        "link"
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function getLinkAttribute()
    {
        return url("/api/images/". $this->name);
    }
}
