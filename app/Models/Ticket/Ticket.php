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
use App\Models\Customer\Customer;
use App\Models\StatusReply;
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
        "attended_by_user_id",
        "last_replied_internal_user_id",
        "reply_status_to_contact_id",
        "reply_status_to_users_id",
    ];

    protected $appends = [
        "encript_id",
        "first_reply_time_ago",
        "diff_tracked",
        "diff_tracked_hours",
        "reply_status_to_internal",
        "reply_status",
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

    public function getReplyStatusToInternalAttribute()
    {

        if(is_null($this->last_replied_internal_user) || is_null(request()->user()))
            return StatusReply::find(5);

        if(request()->user()->id == $this->last_replied_internal_user_id){
            return StatusReply::find(8);
        }

        $repy = StatusReply::find(5);
        $repy->description = "{$this->last_replied_internal_user->name} ha respondido";
        $repy->show_in_list = 1;
        return $repy;
    }

    public function getReplyStatusAttribute()
    {

        $user = request()->user();

        if($this->status_ticket->can_close ?? false){
            $status = $this->status_ticket;
            $status->color = '#fff';
            $status->background_color = '#FF586B';
            $status->border_color = '#FF586B';

            return $status;
        }

        if($user){

            if($user->hasPermissionTo('portal admin')){
                return $this->reply_status_to_users;
            }

        }

        return $this->reply_status_to_contact;
    }

    public function reply_status_to_contact()
    {
        return $this->belongsTo(StatusReply::class, 'reply_status_to_contact_id');
    }
    
    public function reply_status_to_users()
    {
        return $this->belongsTo(StatusReply::class, 'reply_status_to_users_id');
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

    public function attended_by_user()
    {
        return $this->belongsTo(User::class, "attended_by_user_id");
    }

    public function last_replied_internal_user()
    {
        return $this->belongsTo(User::class, "last_replied_internal_user_id");
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class)->withTrashed();
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class)->where("channel", "CUSTOMER");
    }

    public function messages_internal()
    {
        return $this->hasMany(TicketMessage::class)->where("channel", "INTERNAL");
    }

    public function files(){
        return $this->hasManyThrough(File::class, TicketMessage::class, 'ticket_id', 'model_id')->where("model_type", TicketMessage::class);
    }

    public function getDiffTrackedAttribute(){
        
        if(is_null($this->tracked_initial_time) || is_null($this->tracked_end_time))
            return "Sin definir";

        $carbonInitial = Carbon::parse($this->tracked_initial_time);
        $carbonEnd = Carbon::parse($this->tracked_end_time);

        $diffSeconds = $carbonInitial->DiffInSeconds($carbonEnd);
        
        return CarbonInterval::seconds($diffSeconds)->cascade()->forHumans(['parts' => 2]);
    }

    public function getDiffTrackedHoursAttribute(){
        
        if(is_null($this->tracked_initial_time) || is_null($this->tracked_end_time))
            return 0;

        $carbonInitial = Carbon::parse($this->tracked_initial_time);
        $carbonEnd = Carbon::parse($this->tracked_end_time);

        return $carbonInitial->diffInHours($carbonEnd);        
    }

    public static function boot(){
        parent::boot();

        Ticket::observe(TicketTimeline::class);
    }
}
