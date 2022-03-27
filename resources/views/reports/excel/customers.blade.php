<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Clientes</title>
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
                <b>Lista de Clientes</b>
            </td>
        </tr>
    </table>
    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>            
            <tr> 
                <td class="align-center fcw bg-blue">
                    <b>Nombre</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Correo</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Teléfono</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Cédula</b>
                </td>
                <td class="align-center fcw bg-blue">
                    <b>Fecha de Creación</b>
                </td>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>
                        {{ $item->name }}
                    </td>
                    <td>
                        {{ $item->dni }}
                    </td>
                    
                    <td>
                        {{ $item->email }}
                    </td>
                    <td>
                        {{ $item->phone }}
                    </td>
                    <td>
                        {{ $item->created_at->format("d/m/Y H:i A") }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
<?php
