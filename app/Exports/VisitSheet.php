<?php

namespace App\Exports;

use App\Models\Question\Question;
use App\Models\Visit\Visit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\BeforeSheet;

class VisitSheet implements FromView, WithEvents, WithTitle
{
    use RegistersEventListeners;

    /**
     * Visita
     */
    private $visit;

    function __construct(Visit $data)
    {
        $this->visit = $data;
    }
   
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Visita';
    }

    public function view(): View
    {

        $questions = Question::with('section')->get();

        /**
         * Mitad de preguntas para distribuir adecuadamente en las dos columnas
         */
        $middleNumber = $questions->count() / 2;

        /**
         * Almacenar las filas totales
         */
        $rowsHtml = [];

        /**
         * Ultima sección agregada
         */
        $lastSectionInserted = '';

        /**
         * Cuando pase la mitad de las preguntas, se comenzará a insertar el segundo bloque del template de row
         * Esta variable sirve para ubicarse dentro del array $rowsHtml. Será un contador en el segundo condicional
         */
        $rowIndexPassed = 0;
        
        foreach ($questions as $question) {

            /**
             * Si la pregunta tiene numero inferior a la media
             */
            if($question->order <= $middleNumber){
                
                /**
                 * Si es una sección diferente, se agrega una fila adicional
                 */
                if($question->section->description != $lastSectionInserted){
                    $lastSectionInserted = $question->section->description;

                    $headerQuestionRow = $this->insertBlockHeader($this->generateRow(), 'blockOne', $question);

                    array_push($rowsHtml, $headerQuestionRow);
                }
                
                $bodyQuestionRow = $this->insertBlock($this->generateRow(), 'blockOne', $question);
                
               array_push($rowsHtml, $bodyQuestionRow);
               
            }
            else{

                 /**
                 * Si es una sección diferente, se agrega una fila adicional
                 */
                if($question->section->description !== $lastSectionInserted){
                    $lastSectionInserted = $question->section->description;

                    $rowsHtml[$rowIndexPassed] = $this->insertBlockHeader($rowsHtml[$rowIndexPassed], 'blockTwo', $question);

                    $rowIndexPassed++;
                }

                $rowsHtml[$rowIndexPassed] = $this->insertBlock($rowsHtml[$rowIndexPassed], 'blockTwo', $question);

                $rowIndexPassed++;

            }

        }

        $rowsHtml = array_map(function($row) {
            return preg_replace('/blockTwo/', '<td></td><td></td><td colspan="3"></td>', $row);
        }, $rowsHtml);

        // dd($rowsHtml);

        // dd($sections);
        return view('exports.visits', [
           'rowsHtml' => $rowsHtml,
           'visit' => $this->visit
        ]);
    }

    /**
     * Insertar bloque en el header (section) de las preguntas
     * 
     * @param $newRow HTML de la fila
     * @param $block Bloque que va a ser reemplazado para pintar el html de la columna
     * @param $question Pregunta de cuestionario
     * 
     */
    private function insertBlockHeader($newRow, $block, $question){

        $tdHtml = "
            <td></td>
            <td colspan='4'>
                {$question->section->description} ({$question->section->questions->count()})
            </td>
        ";

        return preg_replace("{{$block}}", $tdHtml, $newRow);
    }

    /**
     * Insertar bloque en la fila, serían las preguntas
     * 
     * @param $newRow HTML de la fila
     * @param $block Bloque que va a ser reemplazado para pintar el html de la columna
     * @param $question Pregunta de cuestionario
     * 
     */
    private function insertBlock($newRow, $block, $question){
        $questionScore = $this->visit->questions->firstWhere('question_id', $question->id)->score ?? 0; 

        $tdHtml = "
            <td>{$questionScore}</td>
            <td>{$question->order}</td>
            <td colspan='3'>
                {$question->description} - S / N ({$question->max_score})
            </td>  
        ";

        return preg_replace("{{$block}}", $tdHtml, $newRow);

    }

    private function generateRow()
    {
        return "
            <tr>
                blockOne
                <td></td>
                blockTwo
            </tr>
        
        ";
    }

    public static function beforeSheet(BeforeSheet $event)
    {

        /**
         * General styles
         */
        $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setName('Arial');    
        $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setSize(10);   
        $event->sheet->getRowDimension('1')->setRowHeight(20);
        $event->sheet->getRowDimension('5')->setRowHeight(4);
        // $event->sheet->getStyle()->applyFromArray([
        //     'borders' => array(
        //         'allborders' => array(
        //             'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //             'color' => array('rgb' => 'DDDDDD')
        //         )
        //     )
        // ]);
 
        /**
         * Referencias del tamaño que deberian tener las columnas agrupadas
         */
        $event->sheet->getColumnDimension('A')->setWidth(8);
        $event->sheet->getColumnDimension('B')->setWidth(4);
        $event->sheet->getColumnDimension('C')->setWidth(20);
        $event->sheet->getColumnDimension('D')->setWidth(20);
        $event->sheet->getColumnDimension('E')->setWidth(20);
        $event->sheet->getColumnDimension('F')->setWidth(2);
        $event->sheet->getColumnDimension('G')->setWidth(8);
        $event->sheet->getColumnDimension('H')->setWidth(4);
        $event->sheet->getColumnDimension('I')->setWidth(20);
        $event->sheet->getColumnDimension('J')->setAutoSize(true);
        $event->sheet->getColumnDimension('K')->setAutoSize(true);


        /**
         * Array styles
         */
        $borderOutlineBlack = [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '00000000'],
            ]
        ];

        $fontArialNarrow = [
            'bold' => true,
            'size' => 10,
            'name' => 'Arial Narrow'
        ];

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
        $event->sheet->mergeCells("A1:K1");
        $event->sheet->getStyle('A1:K1')->applyFromArray([
            'alignment' => $aligmentCenter,
            'font' =>[
                'bold' => true,
                'name' => 'Arial',
                'size' => 11 
            ],
        ]);

        /**
         * Fila 2
         */
        $event->sheet->getStyle('A2:K2')->applyFromArray([ 'alignment' => $aligmentCenter, 'font' => $fontArialNarrow, ]);

        /**
         * Fila 3
         */

        // folio
        $event->sheet->getStyle('A3:K3')->applyFromArray([ 'alignment' => $aligmentCenter, ]);

        // campos valores
        // FECHA
        $event->sheet->getStyle('C3')->applyFromArray([ 'borders' => $borderOutlineBlack ]); 
        // SEMANA
        $event->sheet->getStyle('D3')->applyFromArray([ 'borders' => $borderOutlineBlack ]); 
        // GRANJA
        $event->sheet->getStyle('E3')->applyFromArray([ 'borders' => $borderOutlineBlack ]); 
        
        //FLUJO
        $event->sheet->getStyle('I3')->applyFromArray([ 'borders' => $borderOutlineBlack ]); 
        //JEFE GRANJA
        $event->sheet->getStyle('J3')->applyFromArray([ 'borders' => $borderOutlineBlack ]); 
        //APARCERO
        $event->sheet->getStyle('K3')->applyFromArray([ 'borders' => $borderOutlineBlack ]); 
        
        /**
         * Fila 4
         */
        // lotes
        $event->sheet->getStyle('C4')->applyFromArray([ 'font' => $fontArialNarrow, 'alignment' => $aligmentCenter ]); 
        $event->sheet->getStyle('D4')->applyFromArray([ 'borders' => $borderOutlineBlack ]); 
     
        // semanas estancia
        $event->sheet->getStyle('I4')->applyFromArray([ 'font' => $fontArialNarrow, 'alignment' => $aligmentCenter ]);  
        $event->sheet->getStyle('J4')->applyFromArray([ 'borders' => $borderOutlineBlack ]); 
        
        /**
         * Fila 6
         */

        $event->sheet->getStyle('A6:E6')->applyFromArray([ 
            'font' => array_merge($fontArial, ['size' => 9]),
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'D0D0D0']
            ] 
        ]);

        $event->sheet->getStyle('G6:K6')->applyFromArray([ 
            'font' => array_merge($fontArial, ['size' => 9]),
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'D0D0D0']
            ]
        ]);


        $event->sheet->getStyle('A6')->applyFromArray([ 'alignment' => $aligmentCenter, ]);
        $event->sheet->getStyle('B6')->applyFromArray([ 'alignment' => $aligmentCenter, ]);
        $event->sheet->getStyle('C6:E6')->applyFromArray([ ]);

        $event->sheet->getStyle('G6')->applyFromArray([ 'alignment' => $aligmentCenter, ]);
        $event->sheet->getStyle('H6')->applyFromArray([ 'alignment' => $aligmentCenter, ]);
        $event->sheet->getStyle('I6:K6')->applyFromArray([ ]);


        /**
         * Fila N (después de las preguntas)
         */
        $event->sheet->getStyle('B7:E7')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'E3E3E3']
            ],
            'font' => [
                'bold' => true,
                'size' => 10,
                'name' => 'Arial Narrow'
            ]
        ]);

        /**
         * Bloque uno de preguntas
         */
        $sectionsRows = [7,12,18,23,31,35,44];

        foreach ($sectionsRows as $sectionRow) {
            $event->sheet->duplicateStyle($event->sheet->getStyle("B7:E7"), "B{$sectionRow}:E{$sectionRow}");
        }

        for ($i = 8; $i < 53; $i++) { 

            if(in_array($i, $sectionsRows))
                continue;

            $event->sheet->getStyle("B{$i}")->applyFromArray([
                'font' => [
                    'size' => 9,
                    'underline' => true
                ]
            ]);
            $event->sheet->getStyle("C{$i}:E{$i}")->applyFromArray([
                'font' => [
                    'size' => 9,
                    'name' => 'Arial Narrow'
                ]
            ]);
        }

        /**
         * Bloque dos de preguntas
         */

        $sectionsRows = [7,24,35,44];

        foreach ($sectionsRows as $sectionRow) {
            $event->sheet->duplicateStyle($event->sheet->getStyle("B7:K7"), "H{$sectionRow}:K{$sectionRow}");
        }

        for ($i = 8; $i < 53; $i++) { 

            if(in_array($i, $sectionsRows))
                continue;

            $event->sheet->getStyle("H{$i}")->applyFromArray([
                'font' => [
                    'size' => 9,
                    'underline' => true
                ]
            ]);
            $event->sheet->getStyle("I{$i}:K{$i}")->applyFromArray([
                'font' => [
                    'size' => 9,
                    'name' => 'Arial Narrow'
                ]
            ]);
        }
        
        /**
         * Resultados
         */
         $event->sheet->getRowDimension(53)->setRowHeight(20);
         $event->sheet->getStyle("G53:I53")->applyFromArray([ 
             'alignment' => $aligmentCenter, 
             'font' => $fontArial,
             'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'E3E3E3']
             ], 
             'borders' => [
                 'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['argb' => 'ACACAC'],
                 ]
             ] 
        ]);
        $event->sheet->duplicateStyle($event->sheet->getStyle("G53:I53"), "J53:K53");

        /**
         * Nota
         */
        $event->sheet->getRowDimension(54)->setRowHeight(25);
        $event->sheet->getStyle("A54:K54")->applyFromArray([ 
            'font' => [
                'size' => 10,
                'name' => 'Arial Narrow'
                ]
            ]);
        $event->sheet->getStyle("A54:K54")->getAlignment()->setWrapText(true);

        /**
         * Comentarios
         */

        $event->sheet->getStyle("A56:K56")->applyFromArray([
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'font' => [
                'size' => 10,
                'name' => 'Arial'
            ]
        ]);

        for ($i=57; $i <= 64; $i++) { 
            $event->sheet->duplicateStyle($event->sheet->getStyle("A56:K56"), "A{$i}:K{$i}");
        }

        /**
         * Firmas
         */
        $event->sheet->getRowDimension(65)->setRowHeight(30);
        $event->sheet->getStyle("A65:K65")->applyFromArray([
            'alignment' => $aligmentCenter,
            'font' => [
                'size' => 10,
                'name' => 'Arial',
                'color' => array('rgb' => 'D8D8D8'),
            ]
        ]);

        /**
         * Pintar celdas de resultados
         */

         $event->sheet->getStyle("A7")->applyFromArray([
            'font' => [
                'size' => 9,
                'name' => 'Arial Narrow'
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'E3E3E3']
             ], 
             'borders' => [
                 'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['argb' => 'ACACAC'],
                 ]
             ] ,
             'alignment' => $aligmentCenter,
         ]);

         $event->sheet->duplicateStyle($event->sheet->getStyle("A7"), "G7");  

         for ($i=8; $i < 53; $i++) { 
            $event->sheet->duplicateStyle($event->sheet->getStyle("A7"), "A{$i}");  
            $event->sheet->duplicateStyle($event->sheet->getStyle("A7"), "G{$i}");  
         }
    }

}
