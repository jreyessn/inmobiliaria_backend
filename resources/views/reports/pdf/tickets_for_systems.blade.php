<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Tickets por Sistemas</title>

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
                   <h2 class="fcb align-center">Tickets por Sistemas</h2>
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
                <td class="align-center">{{ $item->system->name ?? '' }}</td>
                <td class="align-center">{{ $item->contact->customer->name ?? '' }}</td>
                <td class="align-center">{{ $item->created_at->format('d/m/Y') }}</td>
                <td class="align-center">{{ $item->type_ticket->description }}</td>
                <td class="align-center">{{ $item->user->name ?? '' }}</td>
                <td class="align-center">{{ $item->diff_tracked_hours }}</td>
            </tr>

            @endforeach
        </tbody>
    </table>


</body>

</html>