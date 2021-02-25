<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function createdOptions(){
        return [
            'data' => [
                [
                    'id' => 1,
                    'description' => 'Hoy'
                ],
                [
                    'id' => 2,
                    'description' => 'Ayer'
                ],
                [
                    'id' => 3,
                    'description' => 'Ultimos 3 días'
                ],
                [
                    'id' => 4,
                    'description' => 'Esta semana'
                ],
                [
                    'id' => 5,
                    'description' => 'Ultimos 7 días'
                ],
                [
                    'id' => 6,
                    'description' => 'Este mes'
                ],
                [
                    'id' => 7,
                    'description' => 'Ultimos 30 días'
                ],
            ]
            ];
    }
}
