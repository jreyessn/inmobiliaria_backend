<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tiempo por Sistemas</title>
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
                <b>Tiempo por Sistemas</b>
            </td>
        </tr>
        <tr>
            <td>Desde: {{$since->format('d-m-Y')}}</td>
        </tr>
        
        <tr>
            <td>Hasta: {{$until->format('d-m-Y')}}</td>
        </tr>

    </table>
    <table>
        <thead>
            <tr>

                <td class="align-center fcw bg-blue">
                    <b>Nombre</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Cliente</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Producción</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>App Movil</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Tickets</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Horas</b>
                </td>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td class="align-center">{{ $item->name }}</td>
                <td class="align-center">{{ $item->customer->name ?? '' }}</td>
                <td class="align-center">{{ $item->url_production }}</td>
                <td class="align-center">{{ $item->app_mobile? "Sí" : "No" }}</td>
                <td class="align-center">{{ $item->tickets_count }}</td>
                <td class="align-center">{{ $item->time_in_hours }}</td>
            </tr>

            @endforeach
            <tr>
                <td class="align-center" colspan="4"></td>
                <td class="align-center fcw bg-blue">
                    <b>Total</b>
                </td>
                <td class="align-center">
                    {{ $total_hours }}
                </td>
            </tr>
        </tbody>

    </table>
    <br>

    {{ date('d-m-Y h:i A') }}
</body>
</html>
<?php
