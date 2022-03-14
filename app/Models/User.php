<?php

namespace App\Models;

use App\Models\Contact\Contact;
use App\Models\Coupons\CouponsMovements;
use App\Models\Farm\Farm;
use App\Models\Farm\FarmUser;
use App\Models\Group\Group;
use App\Models\User\UserPreferences;
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
        "preferences"
    ];

    /**
     * Setters attributers
     * 
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function players(){
        return $this->hasMany(UserPlayer::class);
    }

    public function routeNotificationForOneSignal()
    {
        return $this->players()->pluck("player_id");
    }

    public function getPreferencesAttribute(){
        $preferencesDefault = collect(defaultPreferences());
        $preferences        = UserPreferences::where("user_id", $this->id)->get()->flatMap(function($item){
            return [ 
                $item->key => $item->value 
            ];
        });

        return $preferencesDefault->merge($preferences);
    }

}
