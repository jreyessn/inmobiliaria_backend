<?php

namespace App\Http\Controllers\Reports;

use App\Criteria\SinceUntilCreatedAtCriteria;
use App\Criteria\UserAuditCriteria;
use App\Exports\ViewExport;
use App\Http\Controllers\Controller;
use App\Repositories\Coupons\CouponsMovementsRepositoryEloquent;
use App\Repositories\Customer\CustomerRepositoryEloquent;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportsSalesController extends Controller
{

    private $couponsMovementRepositoryEloquent;

    private $customerRepositoryEloquent;

    public function __construct(
        CouponsMovementsRepositoryEloquent $couponsMovementRepositoryEloquent,
        CustomerRepositoryEloquent $customerRepositoryEloquent
    )
    {
        $this->couponsMovementRepositoryEloquent = $couponsMovementRepositoryEloquent;    
        $this->customerRepositoryEloquent        = $customerRepositoryEloquent;    
    }

    /**
     * Reporte de entregas diarias. 
     */
    public function dailyDeliveries(Request $request)
    {
        $request->validate([
            'perPage'       =>  'nullable|integer',
            'page'          =>  'nullable|integer',
            'search'        =>  'nullable|string',
            'orderBy'       =>  'nullable|string',
            'sortBy'        =>  'nullable|in:desc,asc',
            "format"        =>  "in:pdf,excel,json",
            "user_id"       =>  "nullable|string",
            "since"         =>  "nullable|date",
            "until"         =>  "nullable|date",
        ]);

        $this->couponsMovementRepositoryEloquent->pushCriteria(SinceUntilCreatedAtCriteria::class);
        $this->couponsMovementRepositoryEloquent->pushCriteria(UserAuditCriteria::class);

        switch ($request->format) {
            case 'json':

                $perPage    = $request->get('perPage', config('repository.pagination.limit'));
                $data       = $this->couponsMovementRepositoryEloquent->where("type_movement", "Venta")->paginate($perPage);

                return $data;
            break;
            case 'pdf':
                $data = $this->couponsMovementRepositoryEloquent->where("type_movement", "Venta")->get();

                return PDF::loadView('reports/pdf/deliveries', [
                    
                    "data"  => $data,
                    "since" => $request->since? Carbon::parse($request->since) : null,
                    "until" => $request->until? Carbon::parse($request->until) : null,

                ])->download('deliveries.pdf');
            break;
            case 'excel':
                $data = $this->couponsMovementRepositoryEloquent->where("type_movement", "Venta")->get();

                return Excel::download(
                    new ViewExport ([
                        'data' => [
                            "data"  => $data,
                            "since" => $request->since? Carbon::parse($request->since) : null,
                            "until" => $request->until? Carbon::parse($request->until) : null,
                        ],
                        'view' => 'reports.excel.deliveries'
                    ]),
                    'deliveries.xlsx'
                );
            break;

        }

    }

    /**
     * Clientes con cupones por renover
     */

     public function renewalCustomerCoupons(Request $request)
     {
        $request->validate([
            'perPage'       =>  'nullable|integer',
            'page'          =>  'nullable|integer',
            'search'        =>  'nullable|string',
            'orderBy'       =>  'nullable|string',
            'sortBy'        =>  'nullable|in:desc,asc',
            "format"        =>  "in:pdf,excel,json",
            "less_than_coupons" => "nullable|string|numeric"
        ]);

        $less_than_coupons = $request->get("less_than_coupons", 0);

        $this->customerRepositoryEloquent->pushCriteria(SinceUntilCreatedAtCriteria::class);

        switch ($request->format) {
            case 'json':

                $perPage    = $request->get('perPage', config('repository.pagination.limit'));
                $data       = $this->customerRepositoryEloquent->where("coupons", "<", (int) $less_than_coupons)->paginate($perPage);

                return $data;
            break;
            case 'pdf':
                $data = $this->customerRepositoryEloquent->where("coupons", "<", (int) $less_than_coupons)->get();

                return PDF::loadView('reports/pdf/renewal_customers', [
                    
                    "data"  => $data,
                    "less_than_coupons" => $less_than_coupons

                ])->download('renewal_customers.pdf');
            break;
            case 'excel':
                $data = $this->customerRepositoryEloquent->where("coupons", "<", (int) $less_than_coupons)->get();

                return Excel::download(
                    new ViewExport ([
                        'data' => [
                            "data"  => $data,
                            "less_than_coupons" => $less_than_coupons
                        ],
                        'view' => 'reports.excel.renewal_customers'
                    ]),
                    'renewal_customers.xlsx'
                );
            break;

        }
     }

}
