<html>
    <tr>
        <td colspan="{{ $visitMortality->count() + 1 }}">Observación de Morbilidad</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="{{ $visitMortality->count() }}">Edificio</td>
    </tr>
    <tr>
        <td></td>
        @foreach ($visitMortality as $item)
            <td>{{ $item->building }}</td>
        @endforeach
    </tr>
    <tr>
        <td>Inventario Inicial de Sala</td>
        @foreach ($visitMortality as $item)
            <td>{{ $item->inventary_initial }}</td>
        @endforeach
    </tr>
    <tr>
        <td>Mort. Acum: Cant / %</td>
        @foreach ($visitMortality as $item)
            <td>{{ $item->mort_acum }}</td>
        @endforeach
    </tr>
    <tr>
        <td>Mortalidad de Semana Actual</td>
        @foreach ($visitMortality as $item)
            <td>{{ $item->mort_current_week }}</td>
        @endforeach
    </tr>
    <tr>
        <td>Edad del Cerdo</td>
        @foreach ($visitMortality as $item)
            <td>{{ $item->pigs_age }}</td>
        @endforeach
    </tr>
    <tr>
        <td>N° / % Cerdos con Fiebre</td>
        @foreach ($visitMortality as $item)
            <td>{{ $item->pigs_fever }}</td>
        @endforeach
    </tr>
    <tr>
        <td>Actividad: Alta / Moderada / Baja</td>
        @foreach ($visitMortality as $item)
            <td>{{ $item->activity }}</td>
        @endforeach
    </tr>
    <tr>
        <td>{!! '% Tos' !!}</td>
        @foreach ($visitMortality as $item)
            <td>{{ $item->cought }}</td>
        @endforeach
    </tr>
    <tr>
        <td>% Diarrea</td>
        @foreach ($visitMortality as $item)
            <td>{{ $item->diarrhea }}</td>
        @endforeach
    </tr>
    <tr>
        <td># Cerdos Tratados en el Día</td>
        @foreach ($visitMortality as $item)
            <td>{{ $item->pigs_treated_day }}</td>
        @endforeach
    </tr>
</html>