<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class VehicleReportsExport implements FromView, WithEvents
{
	public $params;
	public function __construct( $params ) {
       $this->params = $params;
    }

    public function view(): View
    {
        return view($this->params['view'], $this->params["data"]);
    }

    public function registerEvents(): array
    {
        $rows = $this->params["data"]["rows"];

        return [
            AfterSheet::class  => function(AfterSheet $event) use ($rows){

                $event->sheet->getSheetView()->setZoomScale(85);
                foreach (range("A", "I") as $letter) {
                    $event->sheet->getColumnDimension($letter)->setAutoSize(true);
                }

                $styleBorder = [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ];

                // Header
                $event->sheet->getStyle("A6:M6")->applyFromArray([
                    'borders' => $styleBorder
                ]);

                // Body
                $rowInit = 7;
                foreach ($rows as $key => $row) {
                    $rowIndex = $key * 2 + $rowInit + 1;

                    $event->sheet->getStyle("A{$rowIndex}:M{$rowIndex}")->applyFromArray([
                        'borders' => $styleBorder,
                    ]);

                }

                // Footer (Total)
                $rowIndex = count($rows) * 2 + $rowInit + 1;

                $event->sheet->getStyle("A{$rowIndex}:M{$rowIndex}")->applyFromArray([
                    'borders' => $styleBorder
                ]);

                // Align column A
                $rowHeader = $rowInit - 1;
                $rowCounts = count($rows) * $rowInit;

                $event->sheet->getStyle("A{$rowHeader}:A{$rowCounts}")->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

            }
        ];
    }

}

