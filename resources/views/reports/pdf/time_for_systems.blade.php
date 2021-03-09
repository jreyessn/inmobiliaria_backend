<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Tiempo por Sistemas</title>

</head>

<body>

    <table class="col-10">
        <tbody>
            <tr>
                <td class="width-15">
                    
                    <img src="{{ public_path('logo.png') }}" alt="logo" class="logo">

                </td>
                <td class="width-20">
                    
                </td>
                <td class="align-right">
                <b>Fecha: {{ date('d/m/Y h:i A') }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                   <h2 class="fcb align-center">Tiempo por Sistemas</h2>
                </td>
            </tr>

        </tbody>
    </table>
    <br>
      <strong>Desde:</strong> {{$since->format('d-m-Y')}} <strong>Hasta:</strong> {{$until->format('d-m-Y')}}
    <br>
    <table cellspacing="0" class="col-10 mt10 border-2">
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
                <td colspan="4"></td>
                <td class="align-center fcw bg-blue">
                    <b>Total</b>
                </td>
                <td class="align-center">
                    {{ $total_hours }}
                </td>
            </tr>
        </tbody>
    </table>


</body>

</html>