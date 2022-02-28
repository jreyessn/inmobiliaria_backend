<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Kilometraje por Mes</title>
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
                <b>Reporte de Kilometraje por Mes - {{ $year }}</b>
            </td>
        </tr>
        <tr></tr>
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
                    <td rowspan="2" class="align-center">
                        {{ $item->label }}
                    </td>
                    @foreach ($columns as $column)
                        <td class="align-center">
                            <b>{{ $column["data"][$key]["km_traveled"] }} Km</b>
                        </td>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($columns as $column)
                        <td class="align-center">
                            <b >
                                {{ currency() }}  {{ $column["data"][$key]["amount"] }}
                            </b>
                        </td>
                    @endforeach
                </tr>
            @endforeach
            @if (count($rows) == 0)
                <tr>
                    <td colspan="{{ count($columns) + 1 }}" class="align-center">
                        Sin datos
                    </td>
                </tr>
            @else 
                <tr>
                    <td rowspan="2" class="align-center">
                        <b>Total</b>
                    </td>
                    @foreach ($columns as $column)
                        <td class="align-center">
                            <b>{{ collect($column["data"])->sum("km_traveled") }} Km</b>
                        </td>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($columns as $column)
                        <td class="align-center">
                            <b>
                                {{ currency() }}  {{ collect($column["data"])->sum("amount") }}
                            </b>
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td colspan="9" style="text-align: right"></td>
                    <td colspan="3">
                        <b>Costo Total: </b>
                    </td>
                    <td>
                        <span>{{ currency() }} {{ $totals["amount"] }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="9" style="text-align: right"></td>
                    <td colspan="3">
                        <b>Km Total: </b>
                    </td>
                    <td>
                        <span>{{ $totals["km_traveled"] }} Km</span>                    
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <br>

</body>
</html>

