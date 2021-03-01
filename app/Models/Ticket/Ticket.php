<?php

namespace App\Models\Ticket;

use App\Models\Contact\Contact;
use App\Models\Group\Group;
use App\Models\Priority;
use App\Models\StatusTicket;
use App\Models\TypeTicket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Support\Facades\Crypt;

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
        "spam",
        "deadline",
        "tracked_initial_time",
        "tracked_end_time",
        "first_reply_time",
        "closed_at",
        "reply_status_to_contact",
        "reply_status_to_users",
    ];

    protected $appends = [
        "encript_id",
        "first_reply_time_ago",
    ];

    public function getFirstReplyTimeAgoAttribute(){
        
        if($this->first_reply_time){
            $carbonCreated = Carbon::parse($this->created_at);
            $diff = Carbon::createFromTimeStamp(strtotime($this->first_reply_time))->diffForHumans($carbonCreated);
            
            return trim(preg_replace("/después/", "", $diff));
        }

        return "Sin definir";
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
        return $this->hasMany(TicketMessage::class)->orderBy("created_at", "desc");
    }


}
