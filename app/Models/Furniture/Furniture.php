<?php

namespace App\Models\Furniture;

use App\Models\Audit;
use App\Models\BranchOffices\BranchOffice;
use App\Models\BranchOffices\ModelHasBranchOffice;
use App\Models\Country\City;
use App\Models\Currency\Currency;
use App\Models\Customer\Customer;
use App\Models\Images\Image;
use App\Models\Sale\Credit;
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
        "initial_price",
        "is_credit",
        "currency_id",
        "rate",
        "type_furniture_id",
        "customer_id",
        "city_id",
        "postal_code",
        "region",
        "address",
        "street_number",
        "aditional_info_address",
        "flat",
        "reference_address",
        "getter_user_id",
        "fee_getter",
        "agent_user_id",
        "agent_name",
        "getter_name",
    ];

    protected $with = [
        "measure_unit",
        "type_furniture",
        "city",
        "getter_user",
        "agent_user",
        "images",
        "customer",
        "currency"
    ];

    protected $casts = [
        "initial_price" => "float",
        "unit_price"    => "float",
        "fee_getter"    => "float",
    ];

    protected $appends = [
        "customer_name",
        "customer_dni",
        "percentage_to_initial",
        "percentage_getter",
        "text_credit"
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class)->withTrashed();
    }

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

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function credit(){
        return $this->hasOne(Credit::class);
    }

    public function getCustomerNameAttribute()
    {
        return $this->customer->name ?? '';
    }

    public function getCustomerDniAttribute()
    {
        return $this->customer->dni ?? '';
    }

    public function getPercentageToInitialAttribute()
    {
        return $this->initial_price * 100 / $this->unit_price;
    }

    public function getPercentageGetterAttribute()
    {
        return $this->fee_getter * 100 / $this->unit_price;
    }

    public function getTextCreditAttribute()
    {
        return $this->is_credit? "Crédito" : "Contado";
    }

    public function getAgentNameAttribute($value){
        return strtoupper($value);
    }

    public function getGetterNameAttribute($value){
        return strtoupper($value);
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

    public function branch_offices()
    {
        return $this->hasManyThrough(BranchOffice::class, ModelHasBranchOffice::class, "model_id", "id", "id", "branch_office_id")->where([
            "model_type" => static::class
        ]);
    }
}
