<html>
    <tr>
        <td colspan="5">
            REPORTE RESULTADOS Y FRECUENCIA DE APLICACIÓN DE REPORTE ESTANDAR DE VISITA A GRANJA
        </td>
    </tr>
    <tr>
        <td>Gerente</td>
        <td>
            {{ $frequency['manager']->name ?? 'N/A' }}
        </td>
    </tr>
    <tr>
        <td>Año</td>
        <td>
            {{ $frequency['year'] }}
        </td>
    </tr>
    <tr>
        <td>Desde</td>
        <td>
            {{ $frequency['from'] }}
        </td>
    </tr>
    <tr>
        <td>Hasta</td>
        <td>
            {{ $frequency['to'] }}
        </td>
    </tr>
    <tr>
        <td>Sup / Granja</td>
        <td>Checklist Realizados</td>
        <td>Resultado Promedio</td>
        <td>Resultado Mínimo</td>
        <td>Granja Res. Mínimo</td>
    </tr>
    @php
        $averageTotal = 0;
        $averageMin = 0;
        $sumVisits = 0;
    @endphp
    @foreach ($frequency['data'] as $item)

    @php
        $averageTotal = $averageTotal + $item->average;
        $averageMin = $averageMin + $item->result_min;
        $sumVisits = $sumVisits + $item->visits_completed;
    @endphp
        
    <tr>
        <td>
            {{ $item->name }}
        </td>
        <td>
            {{ $item->visits_completed }}
        </td>
        <td>
            {{ number_format($item->average, 2) }}%
        </td>
        <td>
            {{ number_format($item->result_min, 2) }}%
        </td>
        <td>
            {{ $item->nombre_centro }}
        </td>

    </tr>
    
    @endforeach
    <tr>
        <td>Totales General</td>
        <td>
            {{ $sumVisits }}
        </td>
        <td>
            {{ number_format($averageTotal, 2) }}%
        </td>
        <td>
            {{ number_format($averageMin, 2) }}%
        </td>
        <td></td>
    </tr>

</html>