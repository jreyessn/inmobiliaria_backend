<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Ventas</title>
</head>
<body>
    <table>
        <tr>
            <td>
                <img src="{{ public_path('logo.png') }}" width="120" />
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td></td>
            <td>
                <b>Ventas Realizadas</b>
            </td>
        </tr>

        @if ($since)
            <tr>
                <td></td>
                <td>Desde: {{$since->format('d/m/Y')}}</td>
            </tr>
        @endif

        @if ($until)
            <tr>
                <td></td>
                <td>Hasta: {{$until->format('d/m/Y')}}</td>
            </tr>
        @endif
        
        <tr></tr>
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
                    <b>Razón Social:</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Nombre Comercial:</b>

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
                    <td class="align-center">
                        {{ $item->created_at->format("d/m/Y h:i A") }}
                    </td>
                    <td class="align-center">
                        {{ $item->folio }}
                    </td>
                    <td class="align-center">
                        {{ $item->customer->business_name_street }}
                    </td>
                    <td class="align-center">
                        {{ $item->customer->tradename }}
                    </td>

                    <td class="align-center">
                        {{ $item->quantity }}
                    </td>
                    <td class="align-center">
                        {{ $item->num_invoice ?? '-' }}
                    </td>
                    <td class="align-center">
                        {{ $item->price }}
                    </td>
                    <td class="align-center">
                        {{ $item->total }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6"></td>
                <td class="align-center"> 
                    <b> Total </b> 
                </td>
                <td class="align-center">
                    {{ $data->sum("total") }}
                </td>
            </tr>
        </tbody>
    </table>

    <br>

    {{ date('d-m-Y h:i A') }}
</body>
</html>
<?php
