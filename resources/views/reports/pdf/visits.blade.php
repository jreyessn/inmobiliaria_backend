<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Visitas</title>

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
                    <h2 class="fcb align-center">Visitas</h2>
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

        </tbody>
    </table>

    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>            
            <tr> 
                
                <td class="align-center fcw bg-blue">
                    <b>Fecha y Hora:</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Razón Social:</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Nombre Comercial:</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Visitado por:</b>
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
                        {{ $item->customer->business_name_street }}
                    </td>

                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->customer->tradename }}
                    </td>

   
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->user_created->name }}
                    </td>
 
                </tr>
            @endforeach
        </tbody>
    </table>


</body>

</html>