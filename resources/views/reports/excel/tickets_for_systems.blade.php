<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tickets por Sistemas</title>
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
            <td></td>
            <td>
                <b>Tickets por Sistemas</b>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>Desde: {{$since->format('d-m-Y')}}</td>
        </tr>
        
        <tr>
            <td></td>
            <td>Hasta: {{$until->format('d-m-Y')}}</td>
        </tr>

    </table>
    <table>
        <thead>
            <tr>

                <td class="align-center fcw bg-blue">
                    <b>N°</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Asunto</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Sistema</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Cliente</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Fecha</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Tipo</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Asignado A</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Horas</b>
                </td>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td class="align-center">{{ $item->id }}</td>
                <td class="align-center">{{ $item->title }}</td>
                <td class="align-center">{{ $item->system->name?? '' }}</td>
                <td class="align-center">{{ $item->contact->customer->name ?? '' }}</td>
                <td class="align-center">{{ $item->created_at->format('d/m/Y') }}</td>
                <td class="align-center">{{ $item->type_ticket->description }}</td>
                <td class="align-center">{{ $item->user->name ?? '' }}</td>
                <td class="align-center">{{ $item->diff_tracked_hours }}</td>
            </tr>

            @endforeach
        </tbody>

    </table>
    <br>

    {{ date('d-m-Y h:i A') }}
</body>
</html>
<?php
