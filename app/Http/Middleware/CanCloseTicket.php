<?php

namespace App\Http\Middleware;

use App\Models\StatusTicket;
use Closure;
use Exception;
use Illuminate\Http\Request;

class CanCloseTicket
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
            
            if(is_null($request->status_ticket_id))
                return $next($request);

            $ticketstatus = StatusTicket::find($request->status_ticket_id);
            
            
            if($ticketstatus->can_close && !$request->user()->hasPermissionTo("close tickets")){
                throw new Exception();
            }

            return $next($request);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'No tiene permiso parar cerrar el ticket'
            ], 403);
        }
    }
}
