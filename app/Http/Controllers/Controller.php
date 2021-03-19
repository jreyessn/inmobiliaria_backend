<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

    function expirationsOptions(){
        return [
            'data' => [
                [
                    'id' => 1,
                    'description' => 'Hoy'
                ],
                [
                    'id' => 2,
                    'description' => 'Mañana'
                ],
                [
                    'id' => 3,
                    'description' => 'Próximos 3 días'
                ],
                [
                    'id' => 4,
                    'description' => 'Esta semana'
                ],
                [
                    'id' => 5,
                    'description' => 'Próximos 7 días'
                ],
                [
                    'id' => 6,
                    'description' => 'Próximo mes'
                ],
                [
                    'id' => 7,
                    'description' => 'Próximos 30 días'
                ],
            ]
            ];
    }


}
