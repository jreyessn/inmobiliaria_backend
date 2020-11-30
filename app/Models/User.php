<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasRoles, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'avatar',
        'password_changed_at',
        'phone',
        'type_provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'created_at_format'
    ];

    /**
     *
     * Setters attributers
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function provider(){
        return $this->hasOne('App\Models\Provider\Provider');
    }

    public function applicant_requested(){
        return $this->hasOne(ApplicantProvider::class);
    }

    public function getCreatedAtFormatAttribute(){
        return Carbon::parse($this->created_at)->format('d/m/Y H:i');
    }

    public function type_provider()
    {
        return $this->belongsTo(TypeProvider::class);
    }
    
    public function type_providers()
    {
        return $this->belongsToMany(TypeProvider::class, 'user_type_providers');
    }

}
