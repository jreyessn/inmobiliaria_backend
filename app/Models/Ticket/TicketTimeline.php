<?php

namespace App\Models\Ticket;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketTimeline extends Model
{
    protected $table = 'tickets_timeline';

    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'made_by_user',
        'note',
        'assigned_to_user_id',
    ];

    public function made_user(){
        return $this->belongsTo(User::class, 'made_by_user');
    }
    
    public function assigned_to_user(){
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

}
