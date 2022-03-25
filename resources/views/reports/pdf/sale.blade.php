<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Ventas</title>

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
                    <h2 class="fcb align-center">Lista de Ventas</h2>
                </td>
            </tr>
        </tbody>
    </table>

    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>
            <tr>
                <td class="align-center fcw bg-blue">
                    <b>Número</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Inmueble</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Cliente</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Método de Pago</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Total</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Crédito</b>
                </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $item)
                <tr>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        {{ $item->serie_number }}
                    </td>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        {{ $item->furniture->name ?? '' }}
                    </td>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        {{ $item->customer->name ?? ''}}
                    </td>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        {{ $item->payment_method->name ?? ''}}
                    </td>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        {{ currency() }} {{ number_format($item->total, 2) }}
                    </td>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        {{ $item->is_credit? "Sí" : "No" }}
                    </td>
                </tr>
            @endforeach
            @if (count($data) == 0)
                <tr>
                    <td colspan="6" class="align-center" style="border-top: 1px solid #f3f3f3">
                        Sin datos
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

</body>

</html>