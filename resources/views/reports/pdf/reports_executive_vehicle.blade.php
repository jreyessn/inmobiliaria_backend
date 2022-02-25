<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Reporte de Ejecutivo por Mes</title>

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
                    <h2 class="fcb align-center">Reporte de Ejecutivo por Mes - {{ $year }}</h2>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="col-10">
        <tbody>
            <tr>
                <td colspan="3">
                    <h2 class="fcb align-center">
                        Servicios
                    </h2>
                </td>
            </tr>
        </tbody>
    </table>
  
    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>
            <tr>
                <td class="align-center fcw bg-blue">
                    <b>Unidad</b>
                </td>
                @foreach ($services_month["columns"] as $column)
                    <td class="align-center fcw bg-blue">
                        <b>
                            {{ substr($column["description"], 0, 3) }}
                        </b>
                    </td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($services_month["rows"] as $key => $item)
                <tr>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->label }}
                    </td>
                    @foreach ($services_month["columns"] as $column)
                        <td class="align-center" style="border-top: 1px solid #f3f3f3">
                            <b>{{ $column["data"][$key]["services"] }}</b>
                            <br style="border-top: 1px solid #fff">
                            <b style="color: rgb(2, 114, 24)">
                                {{ currency() }}  {{ $column["data"][$key]["amount"] }}
                            </b>

                        </td>
                    @endforeach
                    
                </tr>
            @endforeach
            @if (count($services_month["rows"]) == 0)
                <tr>
                    <td colspan="{{ count($services_month["columns"]) + 1 }}" class="align-center" style="border-top: 1px solid #f3f3f3">
                        Sin datos
                    </td>
                </tr>
            @else 
                <tr>
                    <td class="align-center" style="border-top: 1px solid #1d3958">
                        <b>Total</b>
                    </td>
                    @foreach ($services_month["columns"] as $column)
                        <td class="align-center" style="border-top: 1px solid #1d3958">
                            <b>{{ collect($column["data"])->sum("services") }}</b>
                            <br style="border-top: 1px solid #fff">
                            <b style="color: rgb(2, 114, 24)">
                                {{ currency() }}  {{ collect($column["data"])->sum("amount") }}
                            </b>
                        </td>
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>

    <table class="col-10">
        <tbody>
            <tr>
                <td colspan="3" style="border-top: 1px solid rgb(145, 144, 144)">
                    <h2 class="fcb align-center">
                        Combustible
                    </h2>
                </td>
            </tr>
        </tbody>
    </table>
  
    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>
            <tr>
                <td class="align-center fcw bg-blue">
                    <b>Unidad</b>
                </td>
                @foreach ($fuels_month["columns"] as $column)
                    <td class="align-center fcw bg-blue">
                        <b>
                            {{ substr($column["description"], 0, 3) }}
                        </b>
                    </td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($fuels_month["rows"] as $key => $item)
                <tr>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->label }}
                    </td>
                    @foreach ($fuels_month["columns"] as $column)
                        <td class="align-center" style="border-top: 1px solid #f3f3f3">
                            <b>{{ $column["data"][$key]["total_loaded"] }} lts</b>
                            <br style="border-top: 1px solid #fff">
                            <b style="color: rgb(2, 114, 24)">
                                {{ currency() }}  {{ $column["data"][$key]["amount"] }}
                            </b>

                        </td>
                    @endforeach
                    
                </tr>
            @endforeach
            @if (count($fuels_month["rows"]) == 0)
                <tr>
                    <td colspan="{{ count($fuels_month["columns"]) + 1 }}" class="align-center" style="border-top: 1px solid #f3f3f3">
                        Sin datos
                    </td>
                </tr>
            @else 
                <tr>
                    <td class="align-center" style="border-top: 1px solid #1d3958">
                        <b>Total</b>
                    </td>
                    @foreach ($fuels_month["columns"] as $column)
                        <td class="align-center" style="border-top: 1px solid #1d3958">
                            <b>{{ collect($column["data"])->sum("total_loaded") }} lts</b>
                            <br style="border-top: 1px solid #fff">
                            <b style="color: rgb(2, 114, 24)">
                                {{ currency() }}  {{ collect($column["data"])->sum("amount") }}
                            </b>
                        </td>
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>

    <table class="col-10">
        <tbody>
            <tr>
                <td colspan="3" style="border-top: 1px solid rgb(145, 144, 144)">
                    <h2 class="fcb align-center">
                        Kilometraje
                    </h2>
                </td>
            </tr>
        </tbody>
    </table>
  
    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>
            <tr>
                <td class="align-center fcw bg-blue">
                    <b>Unidad</b>
                </td>
                @foreach ($km_month["columns"] as $column)
                    <td class="align-center fcw bg-blue">
                        <b>
                            {{ substr($column["description"], 0, 3) }}
                        </b>
                    </td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($km_month["rows"] as $key => $item)
                <tr>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->label }}
                    </td>
                    @foreach ($km_month["columns"] as $column)
                        <td class="align-center" style="border-top: 1px solid #f3f3f3">
                            <b>{{ $column["data"][$key]["km_traveled"] }} Km</b>
                            <br style="border-top: 1px solid #fff">
                            <b style="color: rgb(2, 114, 24)">
                                {{ currency() }}  {{ $column["data"][$key]["amount"] }}
                            </b>

                        </td>
                    @endforeach
                    
                </tr>
            @endforeach
            @if (count($km_month["rows"]) == 0)
                <tr>
                    <td colspan="{{ count($km_month["columns"]) + 1 }}" class="align-center" style="border-top: 1px solid #f3f3f3">
                        Sin datos
                    </td>
                </tr>
            @else 
                <tr>
                    <td class="align-center" style="border-top: 1px solid #1d3958">
                        <b>Total</b>
                    </td>
                    @foreach ($km_month["columns"] as $column)
                        <td class="align-center" style="border-top: 1px solid #1d3958">
                            <b>{{ collect($column["data"])->sum("km_traveled") }} Km</b>
                            <br style="border-top: 1px solid #fff">
                            <b style="color: rgb(2, 114, 24)">
                                {{ currency() }}  {{ collect($column["data"])->sum("amount") }}
                            </b>
                        </td>
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>

    {{-- <table class="col-10">
        <tbody>
            <tr>
                <td colspan="3" style="border-top: 1px solid rgb(145, 144, 144)">
                    <h2 class="fcb align-center">
                        Total Gastos
                    </h2>
                </td>
            </tr>
        </tbody>
    </table> --}}

</body>

</html>