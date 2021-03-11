<?php

namespace App\Models\System;

use App\Models\Ticket\Ticket;
use App\Models\Customer\Customer;
use Illuminate\Support\Facades\Crypt;
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
        'credentials',
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    protected $appends = [
        "encrypted_id",
        "name_url"
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

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }

    public function getEncryptedIdAttribute(){
        return Crypt::encrypt($this->id);
    }

    public function getNameUrlAttribute(){
        return strtolower(str_replace(" ", "-", $this->name));
    }
}
