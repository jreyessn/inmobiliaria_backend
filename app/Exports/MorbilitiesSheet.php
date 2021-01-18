<?php

namespace App\Exports;

use App\Models\Question\Question;
use Illuminate\Contracts\View\View;
use App\Models\Visit\VisitsMortality;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithTitle;

class MorbilitiesSheet implements FromView, WithEvents, WithTitle
{

    use RegistersEventListeners;

    /**
     * Mortalidades
     */
    private $visitMortality;

    function __construct($data)
    {
        $this->visitMortality = $data;

        Cache::put('visitMortality', $data, 1000);
    }
   
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Morbilidad';
    }

    public function view(): View
    {
        // dd($this->visitMortality);

        return view('exports.mortalities', [
           'visitMortality' => $this->visitMortality
        ]);
    }

    public static function beforeSheet(BeforeSheet $event)
    {

        $visitMortality = Cache::get('visitMortality');
        /**
         * General styles
         */
        $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setName('Arial');    
        $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setSize(10);   

 
        /**
         * Referencias del tamaño que deberian tener las columnas agregadas
         */
        $lastLetter = '';
        $letters = [];

        $event->sheet->getColumnDimension('A')->setAutoSize(true);
        foreach (range('B', 'Z') as $key => $value) {
            if($key == $visitMortality->count()){
                $lastLetter = $letters[$key - 1] ?? 'B';
                break;
            }

            $event->sheet->getColumnDimension($value)->setAutoSize(true);

            array_push($letters, $value);
        }

        /**
         * Array styles
         */
        $fontArial = [
            'bold' => true,
            'size' => 10,
            'name' => 'Arial'
        ];

        $aligmentCenter = [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ];


        /**
         * Fila 1
         */
        $event->sheet->getStyle("A1:{$lastLetter}1")->applyFromArray([
            'alignment' => $aligmentCenter,
            'font' =>[
                'name' => 'Arial',
                'size' => 12
            ],
        ]);

        /**
         * Fila 2
         */
        $event->sheet->getStyle("B2:{$lastLetter}2")->applyFromArray([ 
            'alignment' => $aligmentCenter, 
            'font' => $fontArial, 
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ]
            ]
        ]);

        /**
         * Fila 3
         */

         foreach ($letters as $letter) {
            $event->sheet->getStyle("{$letter}3:{$lastLetter}3")->applyFromArray([ 
                'alignment' => $aligmentCenter, 
                'font' =>[
                    'name' => 'Arial',
                    'size' => 10 
                ], 
                'borders' => [
                    'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                    ]
                ]
            ]);
         }

        /**
         * Fila 4 A 12
         */
        foreach ($letters as $letter) {

            for ($i=4; $i < 13; $i++) { 
                $event->sheet->getStyle("{$letter}{$i}")->applyFromArray([ 
                    'alignment' => $aligmentCenter, 
                    'font' =>[
                        'name' => 'Arial',
                        'size' => 10 
                    ], 
                    'borders' => [
                        'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                        ]
                    ]
                ]);
            }

        }

    }

}
