<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Inmuebles</title>

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
                    <h2 class="fcb align-center">Lista de Inmuebles</h2>
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
                    <b>Detalles</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Dirección</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Área</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Ciudad</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Valor Unitario</b>
                </td>
                
                <td class="align-center fcw bg-blue">
                    <b>Inicial</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Cliente</b>
                </td>

                <td class="align-center fcw bg-blue">
                    <b>Responsable</b>
                </td>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $item)
                <tr>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        {{ $item->name }} <br> {{ $item->type_furniture->name ?? '' }}
                    </td>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        <strong>Pisos:</strong> {{ $item->flat == 0? 'Una sola planta' : $item->flat . ' pisos'  }}
                        <br>
                        <strong>Dormitorios:</strong> {{ $item->bedrooms  }}
                        <br>
                        <strong>Baños:</strong> {{ $item->bathrooms  }}
                        <br>
                        <strong>Garajes Cubiertos:</strong> {{ $item->covered_garages  }}
                        <br>
                        <strong>Garajes Descubiertos:</strong> {{ $item->uncovered_garages  }}
                    </td>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        @if ($item->address)
                            {{ $item->address }}
                            <br>
                        @endif
                        @if ($item->street_number)
                            {{ $item->street_number }}
                            <br>
                        @endif
                        @if ($item->region)
                            {{ $item->region }}
                        @endif
                    </td>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        {{ $item->area }}{{ $item->area? ($item->measure_unit->name ?? '') : '' }}
                    </td>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        {{ $item->city->name ?? '' }}
                    </td>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        {{ currency() }} {{ number_format($item->unit_price, 2) }}
                    </td>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        {{ currency() }} {{ number_format($item->initial_price, 2) }}
                    </td>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        {{ $item->customer->name ?? '' }}
                    </td>
                    <td class="align-center"  style="border-top: 1px solid #f3f3f3">
                        {{ $item->agent_user->name ?? '' }}
                    </td>
                </tr>
            @endforeach
            @if (count($data) == 0)
                <tr>
                    <td colspan="9" class="align-center" style="border-top: 1px solid #f3f3f3">
                        Sin datos
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

</body>

</html>