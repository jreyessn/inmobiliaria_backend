<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Visitas</title>
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
                <b>Visitas</b>
            </td>
        </tr>

        <tr>
            <td>Fecha: {{ now()->format('d/m/Y h:i A')}}</td>
        </tr>

        <tr></tr>
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
                <td class="align-center">
                    {{ $item->created_at->format("d/m/Y h:i A") }}
                </td>

                <td class="align-center">
                    {{ $item->customer->business_name_street }}
                </td>

                <td class="align-center">
                    {{ $item->customer->tradename }}
                </td>


                <td class="align-center">
                    {{ $item->user_created->name }}
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
