<?php

namespace App\Models\Provider;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Observers\ProviderSapAuthorizeLogObserver;
use Spatie\Permission\Models\Role as ModelsRole;

class ProviderSapAuthorization extends Model
{
    protected $fillable = [
        'provider_sap_id',
        'approved',
        'approved_at',
        'note',
        'user_id',
        'role_id'
    ];

    protected $appends = [
        'created_at_format',
        'approved_text'
    ];

    protected $with = [
        'role',
        'user'
    ];

    private $status = ['En espera', 'Aprobado', 'Rechazado'];

    public function getApprovedTextAttribute(){
        return $this->status[$this->approved];
    }

    public function role(){
        return $this->belongsTo(ModelsRole::class);
    }

    public function getCreatedAtFormatAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y H:i');
    }

    public function getUpdatedAtFormatAttribute()
    {
        return Carbon::parse($this->updated_at)->format('d/m/Y H:i');
    }

    public function provider_sap(){
        return $this->belongsTo('App\Models\Provider\ProviderSap');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    protected static function boot(){
        parent::boot();

        ProviderSapAuthorization::observe(ProviderSapAuthorizeLogObserver::class);
    }
}
