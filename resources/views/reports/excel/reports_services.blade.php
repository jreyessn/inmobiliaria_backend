<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reportes de Servicio</title>
</head>
<body>
    <table>
        <tr>
            <td>
                <img src="{{ public_path('logo.png') }}" width="120" />
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td>
                <b>Reporte de Servicios Realizados</b>
            </td>
        </tr>
        
        @if ($since)
            <tr>
                <td>Desde: {{ $since->format('d/m/Y')}}</td>
            </tr>
        @endif

        @if ($until)
            <tr>
                <td>Hasta: {{ $until->format('d/m/Y')}}</td>
            </tr>
        @endif

        <tr></tr>
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
            </tr>

        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td class="align-center">
                        {{ $item->completed_at->format("d/m/Y H:i A") }}
                    </td>
                    <td class="align-center">
                        {{ $item->equipment->name }}
                    </td>
                    <td class="align-center">
                        {{ $item->equipments_part->name }}
                    </td>
                    <td class="align-center">
                        {{ $item->user_assigned->name ?? '-' }}
                    </td>
                    <td class="align-center">
                        {{ $item->type_service->name }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

</body>
</html>
<?php
