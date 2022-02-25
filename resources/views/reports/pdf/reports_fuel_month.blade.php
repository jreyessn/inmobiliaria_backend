<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Reporte de Combustible por Mes</title>

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
                    <h2 class="fcb align-center">Reporte de Combustible por Mes - {{ $year }}</h2>
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
                @foreach ($columns as $column)
                    <td class="align-center fcw bg-blue">
                        <b>
                            {{ substr($column["description"], 0, 3) }}
                        </b>
                    </td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $key => $item)
                <tr>
                    <td class="align-center" style="border-top: 1px solid #f3f3f3">
                        {{ $item->label }}
                    </td>
                    @foreach ($columns as $column)
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
            @if (count($rows) == 0)
                <tr>
                    <td colspan="{{ count($columns) + 1 }}" class="align-center" style="border-top: 1px solid #f3f3f3">
                        Sin datos
                    </td>
                </tr>
            @else 
                <tr>
                    <td class="align-center" style="border-top: 1px solid #1d3958">
                        <b>Total</b>
                    </td>
                    @foreach ($columns as $column)
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


</body>

</html>