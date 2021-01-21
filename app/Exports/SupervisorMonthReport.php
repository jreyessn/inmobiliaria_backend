<?php 

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class SupervisorMonthReport implements FromView, WithCharts, WithEvents
{
    
    function __construct($supervisorData)
    {
        $this->supervisorData = $supervisorData;   
    }

    public function view(): View
    {
        return view('exports.supervisor_month', [
            'supervisorData' => $this->supervisorData,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class  => function(BeforeSheet $event){

                $event->sheet->getSheetView()->setZoomScale(85);
            }
        ];
    }

   
    public function charts()
    {
        $cantRows = $this->supervisorData->count() + 10;

        $dataSeriesLabels1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$1', null, 1), 
        ];

        $xAxisTickValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$'.$cantRows, null, 0.5),
        ];

        $dataSeriesValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$'.$cantRows, null, 100),
        ];
        
        // Build the dataseries
        $series1 = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            DataSeries::GROUPING_STANDARD, // plotGrouping
            range(0, count($dataSeriesValues1) - 1), // plotOrder
            $dataSeriesLabels1, // plotLabel
            $xAxisTickValues1, // plotCategory
            $dataSeriesValues1          // plotValues
        );
        
        // Set the series in the plot area
        $plotArea1 = new PlotArea(null, [$series1]);
        
        $title1 = new Title('Resultado por Supervisor por Mes de Visitas a Granjas');
        
        // Create the chart
        $chart1 = new Chart(
            'chart1', // name
            $title1, // title
            null, // legend
            $plotArea1, // plotArea
            true, // plotVisibleOnly
            DataSeries::EMPTY_AS_GAP, // displayBlanksAs

        );
        
        // Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('D1');
        $chart1->setBottomRightPosition('R24');

        return $chart1;
    }


}
