<?php

namespace App\Repositories\Ticket;

use App\Models\Contact\Contact;
use App\Models\File as ModelsFile;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Ticket\TicketMessageRepository;
use App\Models\Ticket\TicketMessage;
use App\Validators\Ticket\TicketMessageValidator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

/**
 * Class TicketMessageRepositoryEloquent.
 *
 * @package namespace App\Repositories\Ticket;
 */
class TicketMessageRepositoryEloquent extends BaseRepository implements TicketMessageRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TicketMessage::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function save(array $data, $userId = null, $via = "PORTAL")
    {
        $store = $this->create([
            "via" => $via,
            "cc" => $data["cc"] ?? null,
            "user_id" => $userId,
            "message" => $data["message"],
            "ticket_id" => $data["ticket_id"],
            "channel" => $data["channel"] ?? "CUSTOMER"
        ]);

        $this->saveFiles($data["files"] ?? [], $store->id);
            
        if($store->channel == "CUSTOMER"){
            $store->ticket->update(['last_replied_at' => now()]);
        }
    }

    private function saveFiles($files, $ticket_message_id)
    {
        foreach ($files as $file) {

            $fileStore = ModelsFile::create([
                "model_type" => TicketMessage::class,
                "model_id" => $ticket_message_id,
            ]);

            $fileContract =  new File($file);

            $fileStore->original_name = $file->getClientOriginalName();
            $fileStore->name =  basename(Storage::disk('local')->putFileAs("files", $fileContract, "{$fileStore->id}-{$file->hashName()}"));
            $fileStore->save();
        }
    }

    public function getMessages($ticket_id, $channel){
        return $this->with(['files', 'user'])
                     ->orderBy('created_at', 'desc')
                     ->where([
                         'ticket_id' => $ticket_id,
                         'channel' => $channel
                     ])->get();
    }
    
}
