<html>
    <tr>
        <td>Supervisor</td>
        <td>Promedio</td>
    </tr>
    @foreach ($supervisorData as $item)
        <tr>
            <td>
                {{ $item->name }}
            </td>
            <td>
                {{ number_format($item->average_result, 2) }}
            </td>
        </tr>
    @endforeach
</html>