<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Document::query()->delete();

        $data = [
            [
                "title" => "Acta Constitutiva",
                'file_input_name' => 'acta_constitutiva_file',
                'folder' => 'actas_constitutivas',
                "example" => "example1.pdf",
            ],
            [
                "title" => "Constancia de Situación Fiscal",
                'file_input_name' => 'constancia_situacion_fiscal_file',
                'folder' => 'constancias_situaciones',
                "example" => "example2.pdf",
            ],
            [
                "title" => "Copia de Identificación Oficial",
                'file_input_name' => 'copia_identificacion_file',
                'folder' => 'identificaciones',
                "example" => "example3.pdf",
            ],
            [
                "title" => "Formato 32D",
                'file_input_name' => 'formato_32d_file',
                'folder' => '32d',
                "example" => "example4.pdf",
            ],
            [
                "title" => "Copia de la Caratula de Estado de Cuenta Reciente",
                'file_input_name' => 'estado_cuenta_file',
                'folder' => 'estados_cuentas',
                "example" => "example5.pdf",
            ],            
            [
                "title" => "Comprobante de Domicilio",
                'file_input_name' => 'comprobante_domicilio_file',
                'folder' => 'comprobantes_domicilios',
                "example" => "example6.pdf",
            ],
            [
                "title" => "Cedula de Cuotas IMSS e INFONAVIT",
                'file_input_name' => 'imss_file',
                'folder' => 'imss',
                "example" => "example7.pdf",
            ],
            [
                "title" => "TAX ID Number (Form W-9)",
                'file_input_name' => 'rfc_file',
                'folder' => 'rfc',
                "example" => "example8.pdf",
            ],
            [
                "title" => "Owner's ID",
                'file_input_name' => 'owner_file',
                'folder' => 'owners',
                "example" => "example9.pdf",
            ],
            [
                "title" => "Account / Routing number confirmation for Wire Transfer",
                'file_input_name' => 'account_routing_file',
                'folder' => 'account_routings',
                "example" => "example10.pdf",
            ],
            [
                "title" => "Poder de Representante Legal",
                'file_input_name' => 'power_legal_representative_file',
                'folder' => 'power_legal_representative',
                "example" => "example10.pdf",
            ],

        ];

        foreach ($data as $value) {
            Document::create($value);
         }

    }
}
