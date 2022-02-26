<?php

namespace App\Exports;

use App\Repositories\Survey\SurveyRepositoryEloquent;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class VehicleReportExecutiveExport implements WithMultipleSheets
{
	public $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function sheets(): array
    {
        $sheets = [
            new VehicleReportsExport([
                "data" => array_merge($this->params["data"]["fuels_month"], [
                    "year" => $this->params["data"]["year"]
                ]),
                "view" => "reports.excel.reports_fuel_month"
            ]),
            new VehicleReportsExport([
                "data" => array_merge($this->params["data"]["km_month"], [
                    "year" => $this->params["data"]["year"]
                ]),
                "view" => "reports.excel.reports_km_month"
            ]),
            new VehicleReportsExport([
                "data" => array_merge($this->params["data"]["services_month"], [
                    "year" => $this->params["data"]["year"]
                ]),
                "view" => "reports.excel.reports_services_month"
            ]),
        ];

        return $sheets;
    }
}
