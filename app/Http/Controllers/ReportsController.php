<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Exports\ViewExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\System\SystemRepositoryEloquent;
use App\Repositories\Ticket\TicketRepositoryEloquent;
use Barryvdh\DomPDF\Facade as PDF;

class ReportsController extends Controller
{

    private $systemRepository;
    private $ticketsRepository;

    function __construct(
        SystemRepositoryEloquent $systemRepository,
        TicketRepositoryEloquent $ticketsRepository

    )
    {
        $this->systemRepository = $systemRepository;
        $this->ticketsRepository = $ticketsRepository;
    }

    public function timeForSystems(Request $request)
    {
        $request->validate([
            "since" => "nullable|date",
            "until" => "nullable|date",
            "system_id" => "nullable|string",
            "format" => "nullable|in:pdf,excel,json",

        ]);

        $system_id = $request->get("system_id", null);
        $explodeSystems = explode(",", $system_id);

        $systems = $this->systemRepository->when($request->since && $request->until, function($query) use ($request){
            $query->whereHas("tickets", function($query) use ($request){
                $query->whereBetween("created_at", [$request->since, $request->until]);
            });
        })
        ->when($system_id, function($query) use ($explodeSystems){
            $query->whereIn("id", $explodeSystems);
        })
        ->orderBy("created_at", "desc")
        ->get()
        ->map(function($item){
            $item->time_in_hours = $item->tickets->reduce(function($carry, $ticket){
                return $carry + $ticket->diff_tracked_hours;
            }, 0);
            $item->tickets_count = $item->tickets()->count();
            
            $item->setHidden([ "tickets" ]);
            
            return $item;
        });
            
            $data = [
            "data" => $systems,
            "since" => Carbon::parse($request->since),
            "until" => Carbon::parse($request->until),
            "total_hours" => $systems->reduce(function($carry, $item){
                return $carry + $item->time_in_hours;
            }, 0)
        ];

        switch ($request->get('format')) {
            case 'pdf':
                return PDF::loadView('reports/pdf/time_for_systems', $data)->download('time_for_systems.pdf');
            break;
            case 'excel':
                return Excel::download(
                    new ViewExport ([
                        'data' => $data,
                        'view' => 'reports.excel.time_for_systems'
                    ]),
                    'time_for_systems.xlsx'
                );
            break;
            
            default:
                return $data;
            break;
        }

    }

    public function ticketsReport(Request $request){
        $request->validate([
            "since" => "nullable|date",
            "until" => "nullable|date",
            "system_id" => "nullable|string",
            "format" => "nullable|in:pdf,excel,json",
            "type_ticket_id" => "nullable|string",
            "user_id" => "nullable|string",
            "customer_id" => "nullable|string",
        ]);


        $tickets = $this->ticketsRepository->with(["contact.customer", "type_ticket", "user", "system"])
        
        ->when($request->since && $request->until, function($query) use ($request){
            $query->whereBetween("created_at", [$request->since, $request->until]);
        })
        ->when($request->get("system_id", null), function($query) use ($request){
            $explodeData = $request->get("system_id", null);
            $explodeData = explode(",", $explodeData);
    
            $query->whereIn("system_id", $explodeData);
        })
        ->when($request->get("type_ticket_id", null), function($query) use ($request){
            $explodeData = $request->get("type_ticket_id", null);
            $explodeData = explode(",", $explodeData);
    
            $query->whereIn("type_ticket_id", $explodeData);
        })
        ->when($request->get("user_id", null), function($query) use ($request){
            $explodeData = $request->get("user_id", null);
            $explodeData = explode(",", $explodeData);
    
            $query->whereIn("user_id", $explodeData);
        })
        ->when($request->get("customer_id", null), function($query) use ($request){
            $query->whereHas("contact", function($query) use ($request){
                $explodeData = $request->get("customer_id", null);
                $explodeData = explode(",", $explodeData);

                $query->whereIn("customer_id", $explodeData);
            });
    
        })
        ->orderBy("created_at", "desc")
        ->get();

        $data = [
            "data" => $tickets,
            "since" => Carbon::parse($request->since),
            "until" => Carbon::parse($request->until),
        ];

        switch ($request->get('format')) {
            case 'pdf':
                return PDF::loadView('reports/pdf/tickets_for_systems', $data)->download('tickets_for_systems.pdf');
            break;
            case 'excel':
                return Excel::download(
                    new ViewExport ([
                        'data' => $data,
                        'view' => 'reports.excel.tickets_for_systems'
                    ]),
                    'tickets_for_systems.xlsx'
                );
            break;
            
            default:
                return $data;
            break;
        }


    }
}
