<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Clientes</title>

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
                    <h2 class="fcb align-center">Lista de Clientes</h2>
                </td>
            </tr>
        </tbody>
    </table>

    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>
            <tr> 
                <td class="align-center fcw bg-blue">
                    <b>Nombre</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Cédula</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Correo</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Teléfono</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Fecha de Creación</b>
                </td>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $item)
                <tr>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->name }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->dni }}
                    </td>
                    
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->email }}
                    </td>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->phone }}
                    </td>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        {{ $item->created_at->format("d/m/Y H:i A") }}
                    </td>
                </tr>
            @endforeach
            @if (count($data) == 0)
                <tr>
                    <td colspan="10" class="align-center" style="border-top: 1px solid #f3f3f3">
                        Sin datos
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

</body>

</html>