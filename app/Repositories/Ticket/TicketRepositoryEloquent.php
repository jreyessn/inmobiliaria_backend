<?php

namespace App\Repositories\Ticket;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Ticket\TicketRepository;
use App\Models\Ticket\Ticket;
use App\Validators\Ticket\TicketValidator;

/**
 * Class TicketRepositoryEloquent.
 *
 * @package namespace App\Repositories\Ticket;
 */
class TicketRepositoryEloquent extends BaseRepository implements TicketRepository
{

    protected $fieldSearchable = [
        'title' => 'like',
        'cc' => 'like',
        'type_ticket_id' => 'like',
        'user.name' => 'like',
        'contact.name' => 'like',
        'contact.email' => 'like',
        'group.name' => 'like',
        'priority.description' => 'like',
        'status_ticket.description' => 'like',
    ];

    private $patchOnly = [
        "priority_id", 
        "system_id", 
        "user_id", 
        "status_ticket_id",
        "type_ticket_id"
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Ticket::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function save(array $data)
    {
        if(is_null($data['type_ticket_id'] ?? null))
            $data["type_ticket_id"] = 1;
        
        if(is_null($data['status_ticket_id'] ?? null))
            $data["status_ticket_id"] = 1;

        if(is_null($data['priority_id'] ?? null))
            $data["priority_id"] = 1;

        if($data['deadline_date'] ?? false){
            $data['deadline'] = "{$data['deadline_date']} {$data['deadline_time']}";
        }

        return $this->create($data);
    }

    public function saveUpdate(array $data, $id)
    {
        $found = $this->find($id);

        if(request()->method() == "PATCH"){
            $found->fill(request()->only($this->patchOnly));
        }
        else{
            $found->fill($data);
        }

        if(($found->status_ticket_id != $found->getOriginal("status_ticket_id")) && $found->status_ticket->can_close == 1){
            $found->closed_at = now();
            $found->save();
        }

        if($found->status_ticket->can_close == 0){
            $found->closed_at = null;
            $found->save();
        }
        
        $found->save();
        
        return $found;
    }
    
}
