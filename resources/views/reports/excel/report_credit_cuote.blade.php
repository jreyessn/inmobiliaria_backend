<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Cuotas</title>
</head>
<body>
    <table>
        <tr>
            <td>
                <img src="{{ public_path() . "/storage/" . config("app.logo_white") }}" width="120" />
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td>
                <b>Reporte de Cuotas</b>
            </td>
        </tr>
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
                    <b>Cuota</b>
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
            @foreach ($data as $item)
                <tr>
                    <td class="align-center" >
                        {{ $item->credit->furniture->name }}
                    </td>
                    <td class="align-center" >
                        {{ $item->credit->furniture->customer->name }}
                    </td>
                    <td class="align-center" >
                        {{ $item->number_letter }}
                    </td>
                    <td class="align-center" >
                        {{ $item->expiration_at->format("d/m/Y") }}
                    </td>
                    <td class="align-center" >
                        {{ $item->payments->count() > 0? $item->payments[0]->created_at->format("d/m/Y") : '' }}
                    </td>
                    <td class="align-center" >
                        {{ number_format($item->total, 2) }}
                    </td>
                    <td class="align-center" >
                        {{ number_format($item->amount_pending, 2) }}
                    </td>
                    <td class="align-center" >
                        {{ number_format($item->payments->sum("total"), 2) }}
                    </td>
                    <td class="align-center" >
                        {{ $item->status_text }}
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
<?php
