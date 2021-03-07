<?php

namespace App\Models\Ticket;

use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Models\Priority;
use App\Models\TypeTicket;
use Carbon\CarbonInterval;
use App\Models\Group\Group;
use App\Models\StatusTicket;
use App\Models\Contact\Contact;
use App\Models\System\System;
use App\Observers\TicketTimeline;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Ticket.
 *
 * @package namespace App\Models\Ticket;
 */
class Ticket extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "title",
        "contact_id",
        "cc",
        "type_ticket_id",
        "status_ticket_id",
        "priority_id",
        "group_id",
        "user_id",
        "system_id",
        "spam",
        "deadline",
        "tracked_initial_time",
        "tracked_end_time",
        "first_reply_time",
        "last_replied_at",
        "closed_at",
        "reply_status_to_contact",
        "reply_status_to_users",
    ];

    protected $appends = [
        "encript_id",
        "first_reply_time_ago",
        "diff_tracked",
    ];

    public function getFirstReplyTimeAgoAttribute(){
        
        if($this->first_reply_time){
            $carbonCreated = Carbon::parse($this->created_at);
            $diff = Carbon::createFromTimeStamp(strtotime($this->first_reply_time))->diffForHumans($carbonCreated);
            
            return trim(preg_replace("/después/", "", $diff));
        }

        return "Sin definir";
    }

    public function getLastRepliedAtAttribute($value){
        return ucwords(Carbon::parse($value)->diffForHumans());
    }

    public function getEncriptIdAttribute()
    {
        return Crypt::encrypt($this->id);
    }

    public function type_ticket()
    {
        return $this->belongsTo(TypeTicket::class);
    }
    
    public function status_ticket()
    {
        return $this->belongsTo(StatusTicket::class);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function files(){
        return $this->hasManyThrough(File::class, TicketMessage::class, 'ticket_id', 'model_id', 'id', 'id');
    }

    public function getDiffTrackedAttribute(){
        
        if(is_null($this->tracked_initial_time) || is_null($this->tracked_end_time))
            return "Sin definir";

        $carbonInitial = Carbon::parse($this->tracked_initial_time);
        $carbonEnd = Carbon::parse($this->tracked_end_time);

        $diffSeconds = $carbonInitial->DiffInSeconds($carbonEnd);
        
        return CarbonInterval::seconds($diffSeconds)->cascade()->forHumans(['parts' => 2]);
    }

    public static function boot(){
        parent::boot();

        Ticket::observe(TicketTimeline::class);
    }
}
