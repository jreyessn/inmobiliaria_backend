<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Reporte de Ventas</title>

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
                    <h2 class="fcb align-center">Reporte de Ventas</h2>
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
                    <b>Fecha:</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Folio:</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Nombre Comercial:</b>

                </td>
                <td class="align-center fcw bg-blue">
                    <b>Razón Social:</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Cupones:</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Factura:</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Precio:</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Importe:</b>
                </td>
            </tr>

        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->created_at->format("d/m/Y h:i A") }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->folio }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->customer->tradename }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->customer->business_name }}
                    </td>
 
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->quantity }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->num_invoice ?? '-' }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ currency() }} {{ $item->price }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ currency() }} {{ $item->total }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" style="border-top: 1px solid #f3f3f3"></td>
                <td class="align-center" style="border-top: 1px solid #f3f3f3"> 
                    <b> Total </b> 
                </td>
                <td class="align-center" style="border-top: 1px solid #f3f3f3">
                    {{ currency() }} {{ $data->sum("total") }}
                </td>
            </tr>
        </tbody>
    </table>


</body>

</html>