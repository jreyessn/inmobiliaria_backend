<?php

namespace App\Http\Middleware;

use App\Models\Ticket\Ticket;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class FileBelongsTicket
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
        try {
            $ticket_id = descryptId($request->id);
            $file_id = $request->file_id;
            
            $ticket = Ticket::find($ticket_id);


            $foundFile = $ticket->files->filter(function($item) use ($file_id){
                return ($item->id == $file_id);
            });
            
        if($foundFile->count() > 0){
            return $next($request);
        }
        throw new Exception();

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Recurso no pertenece al ticket'
            ], 403);
        }

    }
}
