<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class TypesService.
 *
 * @package namespace App\Models\Services;
 */
class TypesService extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $table = "type_services";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name",
        "description",
        "cost"
    ];

    protected $casts = [
        "cost" => "float"
    ];

    public function spare_parts(){
        return $this->belongsToMany(SparePart::class, 'type_services_spare_parts', 'type_service_id' ,'spare_part_id');
    }

}
