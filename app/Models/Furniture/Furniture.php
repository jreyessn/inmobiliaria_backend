<?php

namespace App\Models\Furniture;

use App\Models\Audit;
use App\Models\Country\City;
use App\Models\Images\Image;
use App\Models\Sale\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Furniture.
 *
 * @package namespace App\Models\Furniture;
 */
class Furniture extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $table = "furniture";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name",
        "description",
        "bathrooms",
        "bedrooms",
        "covered_garages",
        "uncovered_garages",
        "measure_unit_id",
        "area",
        "unit_price",
        "sale_price",
        "type_furniture_id",
        "city_id",
        "postal_code",
        "region",
        "address",
        "street_number",
        "aditional_info_address",
        "flat",
        "reference_address",
        "getter_user_id",
        "agent_user_id",
    ];

    protected $with = [
        "measure_unit",
        "type_furniture",
        "city",
        "getter_user",
        "agent_user",
        "images",
    ];

    protected $appends = [
        "is_sold",
    ];

    public function measure_unit()
    {
        return $this->belongsTo(MeasureUnit::class)->withTrashed();
    }

    public function type_furniture()
    {
        return $this->belongsTo(TypeFurniture::class)->withTrashed();
    }

    public function city()
    {
        return $this->belongsTo(City::class)->withTrashed();
    }

    public function getter_user()
    {
        return $this->belongsTo(User::class, "getter_user_id")->withTrashed();
    }

    public function agent_user()
    {
        return $this->belongsTo(User::class, "agent_user_id")->withTrashed();
    }

    public function images()
    {
        return $this->morphMany(Image::class, "model")->where("type", "Gallery");
    }

    public function sale()
    {
        return $this->hasOne(Sale::class);
    }

    public function getIsSoldAttribute()
    {
        return $this->sale()->first()? true : false;
    }

    /**
     * Obtener el usuario que ha creado el registro por medio de la tabla de auditoria.
     */
    public function user_created()
    {
        return $this->hasOneThrough(User::class, Audit::class, "model_id", "id", "id", "user_id")->where([
            "action"     => "CREAR",
            "model_type" => static::class
        ]);
    }
}
