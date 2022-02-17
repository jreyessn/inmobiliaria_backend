<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Reporte de Servicios</title>

</head>

<body>

    <table class="col-10">
        <tbody>
            <tr>
                <td class="width-15">
                    
                    <img src="{{ public_path('logo.png') }}" alt="logo" class="logo">

                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <h2 class="fcb align-center">Reporte de Servicios Realizados</h2>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="col-10">
        <tbody>
            @if ($since)
                <tr>
                    <td class="pl10 width-50">
                        <p><b>Desde:</b> {{ $since->format("d/m/Y") }}</p>
                    </td>
                </tr>
            @endif
            
            @if ($until)
                <tr>
                    <td class="pl10 width-50">
                        <p><b>Hasta:</b> {{ $until->format("d/m/Y") }}</p>
                    </td>
                </tr>
            @endif

        </tbody>
    </table>

    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>
            
            <tr>
                <td class="align-center fcw bg-blue">
                    <b>Fecha/Hora Completado:</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Equipo:</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Pieza:</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Técnico:</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Tipo de Servicio:</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Costo:</b>
                </td>

            </tr>

        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->completed_at->format("d/m/Y H:i A") }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->equipment->name }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->equipments_part->name ?? '' }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->user_assigned->name ?? '-' }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->type_service->name ?? '' }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ currency() }} {{ $item->cost }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


</body>

</html>