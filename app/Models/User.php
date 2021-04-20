<?php

namespace App\Models;

use App\Models\Contact\Contact;
use App\Models\Farm\Farm;
use App\Models\Farm\FarmUser;
use App\Models\Group\Group;
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
        "notification_all_tickets",
        'password_changed_at',
        'phone',
        'slack_player'
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

    protected $appends = [];

    /**
     * Setters attributers
     * 
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function contact()
    {
        return $this->hasOne(Contact::class);
    }

    public function getAvatarAttribute($value)
    {
        return $this->contact? $this->contact->avatar : $value;
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'groups_users');
    }

    public function players(){
        return $this->hasMany(UserPlayer::class);
    }

    public function routeNotificationForSlack($notification)
    {
        return env('SLACK_NOTIFICATION_WEBHOOK');
    }

    public function routeNotificationForOneSignal()
    {
        return $this->players()->pluck("player_id");
    }

}
