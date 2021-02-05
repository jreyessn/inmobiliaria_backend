<html>
    <tr>
        <td>Cost Center</td>
        <td>Promedio</td>
    </tr>
    @foreach ($data as $item)
        <tr>
            <td>
                {{ $item->description }}
            </td>
            <td>
                {{ number_format($item->average_result, 2) }}
            </td>
        </tr>
    @endforeach
</html>