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

    
}
