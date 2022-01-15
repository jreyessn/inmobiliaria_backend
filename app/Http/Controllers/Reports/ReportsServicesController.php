<?php

namespace App\Http\Controllers\Reports;

use App\Criteria\AreaCriteria;
use App\Criteria\EquipmentCriteria;
use App\Criteria\SinceUntilCreatedAtCriteria;
use App\Criteria\UserAssignedCriteria;
use App\Exports\ViewExport;
use App\Http\Controllers\Controller;
use App\Repositories\Services\ServiceRepositoryEloquent;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportsServicesController extends Controller
{
    private $ServiceRepositoryEloquent;

    function __construct(
        ServiceRepositoryEloquent $ServiceRepositoryEloquent
    )
    {
        $this->ServiceRepositoryEloquent = $ServiceRepositoryEloquent;
    }

    /**
     * Reporte para servicios
     */
    public function services(Request $request)
    {
        $request->validate([
            'perPage'       =>  'nullable|integer',
            'page'          =>  'nullable|integer',
            'search'        =>  'nullable|string',
            'orderBy'       =>  'nullable|string',
            'sortBy'        =>  'nullable|in:desc,asc',
            'format'        =>  'nullable|in:pdf,excel,json',
            'since'         =>  'nullable|date',
            'until'         =>  'nullable|date',
            'user_assigned' =>  'nullable|string',
            'equipment_id'  =>  'nullable|string',
            'area_id'       =>  'nullable|string',
        ]);
        
        $perPage = $request->get('perPage', config('repository.pagination.limit'));

        $this->ServiceRepositoryEloquent->pushCriteria(SinceUntilCreatedAtCriteria::class);
        $this->ServiceRepositoryEloquent->pushCriteria(UserAssignedCriteria::class);
        $this->ServiceRepositoryEloquent->pushCriteria(EquipmentCriteria::class);

        switch ($request->format) {
            case 'excel':
                $data = $this->ServiceRepositoryEloquent->where("status", 1)->get();

                return Excel::download(
                    new ViewExport ([
                        'data' => [
                            "data"  => $data,
                            "since" => $request->since? Carbon::parse($request->since) : null,
                            "until" => $request->until? Carbon::parse($request->until) : null,
                        ],
                        'view' => 'reports.excel.reports_services'
                    ]),
                    'reports_services.xlsx'
                );
            break;
                
            case 'pdf':
                $data = $this->ServiceRepositoryEloquent->where("status", 1)->get();

                return PDF::loadView('reports/pdf/reports_services', [
                    
                    "data"  => $data,
                    "since" => $request->since? Carbon::parse($request->since) : null,
                    "until" => $request->until? Carbon::parse($request->until) : null,

                ])->stream('reports_services.pdf');

            break;

            default:    
                return $this->ServiceRepositoryEloquent->where("status", 1)->paginate($perPage);
            break;
        }

    }

}
