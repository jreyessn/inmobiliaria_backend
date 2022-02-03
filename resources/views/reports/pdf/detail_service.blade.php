<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Detalle de Servicio</title>

</head>

<body>

    <table class="col-10">
        <tbody>
            <tr>
                <td class="col-2"></td>
                <td class="fcb align-right">
                    <b>FOLIO: <span class="p-2">{{ $service->id }}</span></b>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="{{ public_path('logo.png') }}" alt="logo" class="logo">
                </td>
                <td>
                    <h2 class="fcb align-center">DETALLE DE SERVICIO</h2>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="col-10 mt10">
        <tbody>
            <tr>
                <td class="fcb align-center">
                    <b>RECIBIDO POR: <span class="border-b-2 p-2">{{ $service->received_by ?? '' }}</span></b>
                </td>
                <td class="fcb align-center">
                    <b>ESTATUS: <span class="border-b-2 p-2">{{ $service->status_text }}</span></b>
                </td>
                <td class="fcb align-center">
                    <b>FECHA: <span class="border-b-2 p-2">{{ $service->event_date->format("d/m/Y") }}</span></b>
                </td>
            </tr>
        </tbody>
    </table> 

    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>
            <tr>
                <td class="align-center fcw bg-brown">
                    <b>DATOS GENERALES:</b>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="pl10">
                    <p><b>Equipo:</b> {{ $service->equipment->name ?? ''}}</p>
                    <p><b>Pieza:</b> {{$service->equipments_part->name ?? 'Sin pieza'}}</p>
                    <p><b>Técnico:</b> {{$service->user_assigned->name ?? 'Sin Asignar'}}</p>
                    <p><b>Granja:</b> {{$service->farm->name ?? ''}}</p>
                    <p><b>Tipo de Servicio:</b> {{$service->type_service->name ?? 'Sin Asignar'}}</p>
                    <p><b>Tipo de Mantenimiento:</b> {{$service->categories_service->name ?? ''}}</p>

                </td>
            </tr>
        </tbody>
    </table>

    <table cellspacing="0" class="col-10 mt10 border-2">
        <thead>
            <tr>
                <td class="align-center fcw bg-brown">
                    <b>Observaciones:</b>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="pl10">
                    <p>{{$service->observation}}</p>
                </td>
            </tr>
        </tbody>
    </table>

    <table cellspacing="0" class="col-10 mt10">
        <thead>
            <tr>
                <td colspan="5" class="align-center fcw bg-brown">
                    <b>Fotos de Evidencias:</b>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($service->evidences as $key => $evidence)
                    <td>
                        <img src="data:image/jpeg;base64,{{ convertTobase64($evidence->name) }}" alt="photo" class="photo-evidence">
                    </td>
                    <?php if (($key + 1) % 2 == 0): ?>
                        </tr>
                        <tr>
                    <?php endif; ?>
                @endforeach
            </tr>
        </tbody>
    </table>

    <br>
    <div class="border-2"></div>
    
    @if($service->signature)
        <table class="col-10 mt10">
            <tr>
                <td colspan="3" class="pl10 align-center">
                    <img src="data:image/jpeg;base64,{{ convertTobase64($service->signature->name) }}" alt="signature" class="photo_signature">
                </td>
            </tr>
            <tr>
                <td></td>
                <td class="align-center border-sig">Firma:</td>
                <td></td>
            </tr>
        </table>
    @endif

</body>

</html>