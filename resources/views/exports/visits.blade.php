<html>

    <tr>
        <td colspan="11">
            REPORTE ESTANDAR DE VISITAS A GRANJA
        </td>
    </tr>
    <tr>
        <td colspan="2"></td>

        <td>Fecha:</td>
        <td>Semana:</td>
        <td>Nombre de Granja:</td>

        <td colspan="3"></td>

        <td>Flujo:</td>
        <td>Jefe de Granja:</td>
        <td>Aparcero:</td>
    </tr>
    <tr>
        <td colspan="2">
            # {{ getCode($visit->id) }}
        </td>

        <td> 
            {{ \Carbon\Carbon::parse($visit->date)->format('d/m/Y') }} 
        </td>
        <td>
            {{ \Carbon\Carbon::parse($visit->date)->weekOfYear }}
        </td>
        <td>
            {{ $visit->farm->nombre_centro }}
        </td>

        <td colspan="3"></td>

        <td>

        </td>
        <td>
            {{ $visit->farm->nombre_gerente }}
        </td>
        <td>
            {{ $visit->farm->nombre_supervisor }}
        </td>

    </tr>
    <tr>
        <td colspan="2"></td>

        <td>Lotes Vuelta Actual:</td>
        <td></td>
        <td></td>

        <td colspan="3"></td>

        <td>Semanas de Estancia:</td>
        <td></td>
        <td></td>
    </tr>
    <tr></tr>
    <tr>
        <td>OBT</td>
        <td>#</td>
        <td colspan="3">CONCEPTO</td>
        <td></td>
        <td>OBT</td>
        <td>#</td>
        <td colspan="3">CONCEPTO</td>
    </tr>
    
    @foreach ($rowsHtml as $row)
        {!! $row !!}
    @endforeach

    <tr>
        <td colspan="6"></td>
        <td colspan="3">
            {{ $visit->result }}
        </td>
        <td colspan="2">
            RESULTADO TOTAL
        </td>
    </tr>

    <tr aria-rowspan="2">
        <td colspan="11">
            Instrucciones: Encerrar S o N como respuesta al concepto, Cuando la respuesta es SI en el concepto, anotar en la  celda OBT (Obtenido) el valor correspondiente (entre parentesis), Respuesta NO, anotar 0 (cero), Anotar puntos cumplidos contra puntos posibles en cada aspecto y sumar los puntos obtenido en cada aspecto para el Resultado Total
        </td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="3">
            COMENTARIOS
        </td>
        <td colspan="8"></td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="3">
            Firma JG
        </td>
        <td colspan="3"></td>
        <td colspan="3">
            Firma Supervisor / Audita
        </td>
    </tr>

    
</html>