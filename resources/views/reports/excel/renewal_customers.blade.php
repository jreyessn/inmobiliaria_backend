<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Clientes por Renovar</title>
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
            <td>
                <b>Clientes por Renovar</b>
            </td>
        </tr>

        <tr>
            <td>Fecha: {{ now()->format('d/m/Y h:i A')}}</td>
        </tr>
    
        <tr>
            <td>
                Menores a {{ $less_than_coupons }} cupones
            </td>
        </tr>

        <tr></tr>
    </table>


    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>            
            <tr>

                <td class="align-center fcw bg-blue">
                    <b>Cliente:</b>
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
                    <td class="align-center">
                        {{ $item->business_name }}
                    </td>
                    <td class="align-center">
                        {{ $item->coupons }}
                    </td>
                    <td class="align-center">
                        {{ $item->coupons_used }}
                    </td>
 
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    {{ date('d-m-Y h:i A') }}
</body>
</html>
<?php
