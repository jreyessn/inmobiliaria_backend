<?php

namespace App\Models\Ticket;

use App\Models\File;
use App\Models\Image;
use App\Models\User;
use App\Observers\UpdateReplyStatusTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class TicketMessage.
 *
 * @package namespace App\Models\Ticket;
 */
class TicketMessage extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $table = "tickets_messages";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "via",
        "cc",
        "user_id",
        "message",
        "ticket_id",
        "channel",
        "forward"
    ];

    protected $appends = [
        "ago",
        "type_reply"
    ];

    public function files(){
        return $this->morphMany(File::class, 'model');
    }

    public function ticket(){
        return $this->belongsTo(Ticket::class);
    }

    public function images(){
        return $this->morphMany(Image::class, 'model');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getAgoAttribute()
    {
        return ucwords($this->created_at->diffForHumans())." (".$this->created_at->isoFormat('DD MMM YYYY, h:mm:ss a').")";
    }

    public function getTypeReplyAttribute()
    {

        if(!$this->forward){
            return "ha respondido (<strong>{$this->via}</strong>)";
        }
        
        return "ha reenviado un mensaje (<strong>{$this->via}</strong>)";
        
    }

    public static function boot(){
        parent::boot();

        TicketMessage::observe(UpdateReplyStatusTicket::class);
    }
    

}
