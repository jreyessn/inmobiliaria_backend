<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Reporte de Cuotas</title>

</head>

<body>

    <table class="col-10">
        <tbody>
            <tr>
                <td class="width-15">
                    
                    <img src="{{ public_path() . "/storage/" . config("app.logo_white") }}" alt="logo" class="logo">

                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <h2 class="fcb align-center">Reporte de Cuotas</h2>
                </td>
            </tr>
        </tbody>
    </table>

    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>
            <tr> 
                <td class="align-center fcw bg-blue">
                    <b>Inmueble</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Cliente</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Letra de Cuota</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Fecha</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Último Pago</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Deuda Total</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Monto Pendiente</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Monto Pagado</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Estatus</b>
                </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $item)
                <tr>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->credit->furniture->name }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->credit->furniture->customer->name }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->number_letter }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->expiration_at->format("d/m/Y") }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->payments->count() > 0? $item->payments[0]->created_at->format("d/m/Y") : '' }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ currency() }} {{ number_format($item->total, 2) }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ currency() }} {{ number_format($item->amount_pending, 2) }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ currency() }} {{ number_format($item->total - $item->amount_pending, 2) }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->status_text }}
                    </td>

                </tr>
            @endforeach
            @if (count($data) == 0)
                <tr>
                    <td colspan="9" class="align-center" style="border-top: 1px solid #f3f3f3">
                        Sin datos
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

</body>

</html>