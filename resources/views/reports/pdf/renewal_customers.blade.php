<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Clientes por Renovar</title>

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
                    <h2 class="fcb align-center">Clientes por Renovar</h2>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="col-10">
        <tbody>
            <tr>
                <td class="pl10 width-50">
                    <p><b>Fecha:</b> {{ now()->format("d/m/Y h:i A") }}</p>
                </td>
            </tr>
            <tr>
                <td class="pl10 width-50">
                    <p>Menores a <b> {{ $less_than_coupons }} cupones </b></p>
                </td>
            </tr>
        </tbody>
    </table>

    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>            
            <tr>

                
                <td class="align-center fcw bg-blue">
                    <b>Razón Social:</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Nombre Comercial:</b>

                </td>

                <td class="align-center fcw bg-blue">
                    <b>Cupones:</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Cupones Utilizados:</b>
                </td>

            </tr>

        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->business_name_street }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->tradename }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->coupons }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->coupons_used }}
                    </td>
 
                </tr>
            @endforeach
        </tbody>
    </table>


</body>

</html>