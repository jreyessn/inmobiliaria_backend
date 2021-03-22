<?php

namespace App\Rules;

use App\Models\Ticket\Ticket;
use Illuminate\Contracts\Validation\Rule;

class HasTrackedTime implements Rule
{

    private $ticket_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($ticket_id)
    {
        $this->ticket_id = $ticket_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $ticket = Ticket::find($this->ticket_id);
        
        if(is_null($ticket))
            return true;
            
        
        if($value == 3){
            if(is_null($ticket->tracked_initial_time) || is_null($ticket->tracked_end_time)){
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Tiene que colocar el tiempo de resolución antes de marcarlo como resuelto.';
    }
}
