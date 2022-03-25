<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Venta</title>
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
                <b>Lista de Ventas</b>
            </td>
        </tr>
        <tr>
            <td>Desde: {{ $since? $since->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
            <td>Hasta: {{ $until? $until->format('d/m/Y') : '' }}</td>
        </tr>
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
            @foreach ($data as $item)
                <tr>
                    <td class="align-center">
                        {{ $item->serie_number }}
                    </td>
                    <td class="align-center">
                        {{ $item->furniture->name ?? '' }}
                    </td>
                    <td class="align-center">
                        {{ $item->customer->name ?? ''}}
                    </td>
                    <td class="align-center">
                        {{ $item->payment_method->name ?? ''}}
                    </td>
                    <td class="align-center">
                        {{ $item->total }}
                    </td>
                    <td class="align-center">
                        {{ $item->is_credit? "Sí" : "No" }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
<?php
