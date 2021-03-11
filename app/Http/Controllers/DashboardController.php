<?php

namespace App\Http\Controllers;

use App\Models\TypeTicket;
use Illuminate\Http\Request;
use App\Models\System\System;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
    function __invoke(Request $request)
    {
        $statistic = $request->get("statistic", null);

        if($statistic == "tickets_urgent" || is_null($statistic))
            $data["tickets_urgent"] = $this->ticketsUrgent($request);

        if($statistic == "open_vs_close" || is_null($statistic))
            $data["open_vs_close"] = $this->OpenVsClose($request);

        if($statistic == "type_tickets" || is_null($statistic))
            $data["type_tickets"] = $this->typeTickets($request);

        if($statistic == "system_tickets_quantity" || is_null($statistic))
            $data["system_tickets_quantity"] = array_values($this->systemTickets($request)->filter(function($item) {
                return ($item->tickets_count > 0);
            })->toArray());

        if($statistic == "system_tickets_time" || is_null($statistic))
            $data["system_tickets_time"] = array_values($this->systemTickets($request)->filter(function($item) {
                return ($item->time_in_hours > 0);
            })->toArray());

        return $data;
    }

    function ticketsUrgent(Request $request){

        $data = Ticket::where([ 'priority_id' => 4 ])->when($request->since && $request->until, function($query) use ($request){
            $query->whereBetween(DB::raw('DATE(created_at)'), [$request->since, $request->until]);
        })->with(["user", "system"])->whereIn('status_ticket_id', [1,2,5])->orderBy('created_at', 'desc');

        $count = clone $data;
        $count = $count->get()->count();
        $data = $data->limit(8)->get();

        return [
            "data" => $data,
            "count" => $count
        ];
    }

    function OpenVsClose(Request $request){
        $open = Ticket::when($request->since && $request->until, function($query) use ($request){
            $query->whereBetween(DB::raw('DATE(created_at)'), [$request->since, $request->until]);
        })->whereHas("status_ticket", function($query){
            $query->where("can_close", 0);
        })->get()->count();
        
        $closed = Ticket::when($request->since && $request->until, function($query) use ($request){
            $query->whereBetween(DB::raw('DATE(created_at)'), [$request->since, $request->until]);
        })->whereHas("status_ticket", function($query){
            $query->where("can_close", 1);
        })->get()->count();

        return [
            "open" => $open,
            "closed" => $closed
        ];
    }

    function typeTickets(Request $request){

        return TypeTicket::get()->map(function($item) use ($request){
            
            $item->tickets_count = $item->tickets()->when($request->since && $request->until, function($query) use ($request){
                $query->whereBetween(DB::raw('DATE(created_at)'), [$request->since, $request->until]);
            })->count();

            $item->percentage_tickets = ($item->tickets_count * 100 ) / Ticket::get()->count();
            $item->percentage_tickets = decimal($item->percentage_tickets);
            
            return $item;
        });
    }

    function systemTickets(Request $request){
        return System::get()->map(function($item) use ($request){

            $timeSumComplete = Ticket::when($request->since && $request->until, function($query) use ($request){
                $query->whereBetween(DB::raw('DATE(created_at)'), [$request->since, $request->until]);
            })->get()->reduce(function($carry, $ticket){
                return $carry + $ticket->diff_tracked_hours;
            }, 0);

            $item->tickets_count = $item->tickets()->when($request->since && $request->until, function($query) use ($request){
                $query->whereBetween(DB::raw('DATE(created_at)'), [$request->since, $request->until]);
            })->count();

            $item->time_in_hours = $item->tickets()->when($request->since && $request->until, function($query) use ($request){
                $query->whereBetween(DB::raw('DATE(created_at)'), [$request->since, $request->until]);
            })->get()->reduce(function($carry, $ticket){
                return $carry + $ticket->diff_tracked_hours;
            }, 0);
            
            $countTickets = Ticket::when($request->since && $request->until, function($query) use ($request){
                $query->whereBetween(DB::raw('DATE(created_at)'), [$request->since, $request->until]);
            })->get()->count();

            $countTickets = ($countTickets == 0)? 1 : $countTickets;
            $timeSumComplete = ($timeSumComplete == 0)? 1 : $timeSumComplete;

            // dump(	$countTickets, $timeSumComplete);

            $item->tickets_percentage = ($item->tickets_count * 100 ) / $countTickets; 
            $item->time_percentage = ($item->time_in_hours * 100 ) / $timeSumComplete;
 
            $item->tickets_percentage = decimal($item->tickets_percentage);
            $item->time_percentage = decimal($item->time_percentage);
 
            $item->setHidden([ "tickets" ]);
            return $item;
        });
    }
}
