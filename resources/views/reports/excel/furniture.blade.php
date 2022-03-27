<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Venta</title>
</head>
<body>
    <table>
        <tr>
            <td>
                <img src="{{ public_path() . "/storage/" . config("app.logo_white") }}" width="120" />
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td>
                <b>Lista de Ventas</b>
            </td>
        </tr>
    </table>
    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>            
            <tr> 
                <td class="align-center fcw bg-blue">
                    <b>Nombre</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Tipo</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Pisos</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Cuartos</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Baños</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Garajes Cubiertos</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Garajes Descubiertos</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Dirección</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Área</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Ciudad</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Valor Unitario</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Inicial Venta</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Cliente</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Responsable</b>
                </td>


            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>
                        {{ $item->name }}
                    </td>
                    <td>
                        {{ $item->type_furniture->name ?? '' }}
                    </td>
                    <td>
                        {{ $item->flat == 0? 'Una sola planta' : $item->flat . ' pisos'  }}
                    </td>
                    <td>
                        {{ $item->bedrooms  }}
                    </td>
                    <td>
                        {{ $item->bathrooms  }}
                    </td>
                    <td>
                         {{ $item->covered_garages  }}
                    </td>
                    <td>
                         {{ $item->uncovered_garages  }}
                    </td>
                    <td>
                        @if ($item->address)
                            {{ $item->address }},
                        @endif
                        @if ($item->street_number)
                            {{ $item->street_number }},
                        @endif
                        @if ($item->region)
                            {{ $item->region }}
                        @endif
                    </td>
                    <td>
                        {{ $item->area }}{{ $item->area? ($item->measure_unit->name ?? '') : '' }}
                    </td>
                    <td>
                        {{ $item->city->name ?? '' }}
                    </td>
                    <td>
                         {{ $item->unit_price }}
                    </td>
                    <td>
                         {{ $item->initial_price }}
                    </td>
    
                    <td>
                        {{ $item->customer->name ?? '' }}
                    </td>
                    <td>
                        {{ $item->agent_user->name ?? '' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
<?php
