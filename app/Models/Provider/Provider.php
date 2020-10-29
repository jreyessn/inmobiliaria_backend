<?php

namespace App\Models\Provider;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "applicant_name",
        "business_name",
        "rfc",
        "business_type_id",
        "business_type_activity", 
        "fiscal_address",
        "street_address",
        "street_number",
        "colony",
        "country_id", 
        "state_id",
        "citiy_id",
        "zip_code",
        "phone",
        "main_shareholder", 
        "sales_representative", 
        "sales_phone",
        "email_quotation", 
        "email_purchase_orders", 
        "website",
        "retention",
        "retention_country", 
        "contracted",
        "contracted_at",
        "contracted_by_user_id",
        "can_edit",
        "note",
        "user_id"
    ];

    protected $casts = [
        'can_edit' => 'boolean',
        'contracted' => 'boolean',
    ];

    protected $with = [
        'country',
        'state',
        'city'
    ];

    public function setRetentionAttribute($value){
        return is_null($value)? 0 : $value;
    }

    public function account_bank(){
        return $this->hasOne('App\Models\Provider\ProviderAccountBank');
    }

    public function provider_sap(){
        return $this->hasOne('App\Models\Provider\ProviderSap');
    }

    public function retention_types(){
        return $this->belongsToMany('App\Models\RetentionType', 'provider_retention_types');
    }

    public function retention_indicators(){
        return $this->belongsToMany('App\Models\RetentionIndicator', 'provider_retention_indicators');
    }

    public function documents(){
        return $this->hasMany('App\Models\Provider\ProviderDocument');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function country(){
        return $this->belongsTo('App\Models\Country');
    }

    public function state(){
        return $this->belongsTo('App\Models\State');
    }

    public function city(){
        return $this->belongsTo('App\Models\City');
    }

    public function business_type(){
        return $this->belongsTo('App\Models\BusinessType');
    }

}
