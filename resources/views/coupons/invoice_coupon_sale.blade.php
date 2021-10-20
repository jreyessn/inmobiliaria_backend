<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Comprobante de Venta</title>

</head>

<body>

    <table class="col-10">
        <tbody>
            <tr>
                <td class="width-15">
                    
                    <img src="{{ public_path('logo.png') }}" alt="logo" class="logo">

                </td>
                <td class="width-20">
                    <h2 class="fcb">{{ getenv('APP_NAME') }}</h2>
                </td>
                <td class="align-right">
                    <b>Folio: # {{ $data->folio }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <h2 class="fcb align-center">Comprobante</h2>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="col-10">
        <tbody>
            <tr>
                <td class="pl10 width-50">
                    <p><b>Cliente:</b> {{ $data->customer->name }}</p>
                    <p><b>Email:</b> {{ $data->customer->email }}</p>
                    <p><b>Teléfono:</b> {{ $data->customer->phone }}</p>
                </td>
                
                <td class="pl10 width-50">
                    <p><b>Fecha de Venta:</b> {{ $data->created_at->format("d/m/Y") }}</p>
                    <p><b>Vendido por:</b> {{ $data->user_created->name }}</p>
                </td>

            </tr>
        </tbody>
    </table>

    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>
            <tr>
                <td colspan="6" class="align-center fcw bg-blue">
                    <b>Productos:</b>
                </td>
            </tr>
            
            <tr>
                <td class="align-center fcw bg-blue">
                    <b>N°:</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Producto:</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Descripción:</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Cantidad:</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Precio:</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Total:</b>
                </td>
            </tr>

        </thead>
        <tbody>
            <tr>
                <td colspan="4"></td>
                <td class="align-center"> <b> Total </b> </td>
            <td class="align-center">$ {{ $data->total }}</td>
            </tr>
        </tbody>
    </table>

    <table cellspacing="0" class="col-10 mt10 border-2">
        <tbody>
            <tr>
                <td class="align-center fcw bg-blue">
                    <b>COMENTARIO:</b>
                </td>
            </tr>
            <tr>
                <td class="pl10">
                   <p>
                       {{ $data->comment }}
                   </p>
                </td>
               
            </tr>

        </tbody>
    </table>

</body>

</html>