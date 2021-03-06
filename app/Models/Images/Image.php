<?php

namespace App\Models\Images;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\File;
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
        "link",
        "mimes"
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function getLinkAttribute()
    {
        return url("/api/images/". $this->name);
    }
    
    public function getMimesAttribute()
    {
        try {
            return Storage::mimeType($this->name) ?? '';
        } catch (\Throwable $th) {
            report($th);
            return null;
        }
    }

}
