<?php 

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

class FrequencyVisitsReport implements FromView, WithEvents
{
    
    function __construct($frequency)
    {
        $this->frequency = $frequency;   
    }

    public function view(): View
    {
        return view('exports.frequency_visits', [
            'frequency' => $this->frequency,
        ]);
    }

   public function registerEvents(): array
    {
        $data = $this->frequency['data'];

        return [
            BeforeSheet::class  => function(BeforeSheet $event) use ($data){

                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setName('Arial');    
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setSize(10);  
        
                /**
                 * Anchos de columnas
                 */
                $event->sheet->getColumnDimension('A')->setWidth(25);
                $event->sheet->getColumnDimension('B')->setWidth(15);
                $event->sheet->getColumnDimension('C')->setWidth(15);
                $event->sheet->getColumnDimension('D')->setWidth(15);
                $event->sheet->getColumnDimension('E')->setWidth(15);
                /**
                 * Fila titulo
                 */
                $event->sheet->getRowDimension(1)->setRowHeight(30);
                /**
                 * Fila headers de tabla
                 */
                $event->sheet->getRowDimension(6)->setRowHeight(20);
        
                /**
                 * Centrar titulo
                 */
                $event->sheet->getStyle('A1:E1')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '9cb9fd'],
                        ]
                    ],
                ]);
                $event->sheet->getStyle('A1:E1')->getAlignment()->setWrapText(true);
        
                /**
                 * Fondo para celdas
                 */
                $event->sheet->getStyle('A2:B5')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'cedbfb']
                    ]
                ]);
                /**
                 * Centrado para datos gerente, granja, fecha
                 */
                $event->sheet->getStyle('B2:B5')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                
                /**
                 * Negritas para titulos
                 */
                $event->sheet->getStyle('A2:A5')->applyFromArray([
                    'font' => [ 'bold' => true ]
                ]);
                /**
                 * Negritas headers tabla y estilos
                 */
                $event->sheet->duplicateStyle($event->sheet->getStyle('A2'), 'A6:E6');
                $event->sheet->getStyle('A6:E6')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'B8B8B8'],
                        ]
                    ]
                ]);
                $event->sheet->getStyle('A6:E6')->getAlignment()->setWrapText(true);

                /**
                 * formato para filas dinamicas en base a la cantidad de datos
                 */
                
                 $rowNumber = 6;

                 for ($i = 0; $i < $data->count(); $i++) { 
                    $rowNumber = $i + 7;
                    $resultMin = $data[$i]->result_min;

                    $event->sheet->getStyle("A{$rowNumber}:E{$rowNumber}")->applyFromArray([
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '9cb9fd'],
                            ]
                        ],
                        'font' => [
                            'bold' => true
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]); 
                    
                    if($resultMin >= 90 && $resultMin <= 100){
                        $event->sheet->getStyle("D{$rowNumber}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['argb' => '02a459']
                            ]
                        ]);
                    }
                    else if($resultMin >= 87 && $resultMin <= 89){
                        $event->sheet->getStyle("D{$rowNumber}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['argb' => '3dbf39']
                            ]
                        ]);
                    }
                    else if($resultMin >= 75 && $resultMin <= 86){
                        $event->sheet->getStyle("D{$rowNumber}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['argb' => 'c8c534']
                            ]
                        ]);
                    }
                    else if($resultMin >= 60 && $resultMin <= 74){
                        $event->sheet->getStyle("D{$rowNumber}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['argb' => 'e18a2d']
                            ]
                        ]);
                    }
                    else if($resultMin < 60){
                        $event->sheet->getStyle("D{$rowNumber}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'color' => ['argb' => 'e13e2d']
                            ]
                        ]);
                    }

                }
                /**
                 * Totalizacion
                 */
                $rowNumber++;
                $event->sheet->duplicateStyle($event->sheet->getStyle('A6:E6'), "A{$rowNumber}:E{$rowNumber}");
        
            },
        ];
    }


}
