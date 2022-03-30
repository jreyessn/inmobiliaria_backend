<?php

namespace App\Http\Controllers\Reports;

use App\Criteria\Reports\CreditCuoteReportCriteria;
use App\Criteria\Service\ServiceCriteria;
use App\Exports\ViewExport;
use App\Http\Controllers\Controller;
use App\Repositories\Sale\CreditCuoteRepositoryEloquent;
use App\Repositories\Services\ServiceRepositoryEloquent;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    private $CreditCuoteRepositoryEloquent;

    function __construct(
        CreditCuoteRepositoryEloquent $CreditCuoteRepositoryEloquent
    )
    {
        $this->CreditCuoteRepositoryEloquent = $CreditCuoteRepositoryEloquent;
    }

    /**
     * Reporte para cuotas
     */
    public function credit_cuotes(Request $request)
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
            'customer_id'   =>  'nullable|string',
            'furniture_id'  =>  'nullable|string',
        ]);
        
        $perPage = $request->get('perPage', config('repository.pagination.limit'));

        $this->CreditCuoteRepositoryEloquent->pushCriteria(CreditCuoteReportCriteria::class);

        switch ($request->format) {
            case 'excel':
                $data = $this->CreditCuoteRepositoryEloquent->get();

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
                $data = $this->CreditCuoteRepositoryEloquent->get();

                return PDF::loadView('reports/pdf/reports_services', [
                    
                    "data"  => $data,
                    "since" => $request->since? Carbon::parse($request->since) : null,
                    "until" => $request->until? Carbon::parse($request->until) : null,

                ])->stream('reports_services.pdf');

            break;

            default:    
                return $this->CreditCuoteRepositoryEloquent->with(["credit.furniture.customer", "payments"])
                                                            ->orderBy("expiration_at", "ASC")
                                                            ->paginate($perPage);
            break;
        }

    }

}
