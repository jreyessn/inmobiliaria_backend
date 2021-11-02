<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Detalle de Entrega</title>

</head>

<body>

    <table class="col-10">
        <tbody>
            <tr>
                <td class="col-2"></td>
                <td class="align-right">
                    <b>FOLIO: 
                        <span class="">{{ $movement->folio }}</span>
                    </b>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="{{ public_path('logo.png') }}" alt="logo" class="logo">
                </td>
                <td>
                    <h2 class="fcb align-center">DETALLE DE ENTREGA</h2>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="col-10 mt10">
        <tbody>
            <tr>
                <td class="fcb align-center">
                <b>FECHA: <span class="border-b-2 p-2">{{ $movement->created_at->format("d/m/Y") }}</span></b>
                </td>
                <td class="fcb align-center">
                <b>HORA: <span class="border-b-2 p-2">{{ $movement->created_at->format("h:i A") }}</span></b>
                </td>
            </tr>
        </tbody>
    </table>

    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>
            <tr>
                <th class="align-center fcw bg-blue">
                    <b>DATOS DE ENTREGA:</b>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="pl10 border-1">
                    <P><b>Nombre Comercial:</b> {{ $movement->customer->tradename ?? '' }}</P>
                </td>
            </tr>
            <tr>
                <td class="pl10 border-1">
                    <P><b>Razón Social:</b> {{ $movement->customer->business_name ?? '' }}</P>
                </td>
            </tr>
            <tr>
                <td class="pl10 border-1">
                    <P><b>Repartidor:</b> {{ $movement->user_created->name ?? '' }}</P>
                </td>
            </tr>
            <tr>
                <td class="pl10 border-1">
                    <P><b>Cantidad de Cupones:</b> {{ $movement->quantity }}</P>
                </td>
            </tr>
            <tr>
                <td class="pl10 border-1">
                    <P><b>Precio por Cupón:</b> {{ currency() }} {{ $movement->price }}</P>
                </td>
            </tr>
            <tr>
                <td class="pl10 border-1">
                    <P><b>Total:</b> {{ currency() }} {{ $movement->total }}</P>
                </td>
            </tr>
            <tr>
                <td class="pl10 border-1">
                    <b>Firma:</b>

                    <br>
                    <br>

                    <div class="align-center">
                        <img src="data:image/png;base64,{{ $sign }}" alt="">
                    </div>

                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>