<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ViewExport implements FromView, WithEvents
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
        return [
            AfterSheet::class  => function(AfterSheet $event){

                $event->sheet->getSheetView()->setZoomScale(85);
                foreach (range("A", "I") as $letter) {
                    $event->sheet->getColumnDimension($letter)->setAutoSize(true);
                }
            }
        ];
    }

}

