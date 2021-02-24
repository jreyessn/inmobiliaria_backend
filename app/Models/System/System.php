<?php

namespace App\Models\System;

use App\Models\Customer\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class System.
 *
 * @package namespace App\Models\System;
 */
class System extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url_production',
        'url_qa',
        'url_admin',
        'url_customers',
        'active',
        'app_mobile',
        'link_download_app',
        'backup',
        'customer_id',
    ];

    protected $hidden = [
        'credentials'
    ];

    public function credentials()
    {
        return $this->hasMany(SystemCredential::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function credentials_users(){
        return $this->credentials()->where('server', null);
    }

    public function credentials_servers(){
        return $this->credentials()->where('server', '!=', null);
    }
}
