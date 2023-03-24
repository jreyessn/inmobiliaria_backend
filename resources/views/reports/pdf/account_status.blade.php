<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Estados de Cuenta</title>

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
                    <h2 class="fcb align-center">Estados de Cuenta</h2>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <strong>Total Créditos: </strong> <span>{{ currency() }} {{ number_format($data->sum("total"), 2) }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <strong>Total Pagado: </strong> <span>{{ currency() }} {{ number_format($data->sum("total_paid"), 2) }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <strong>Total Deuda: </strong> <span>{{ currency() }} {{ number_format($data->sum("total_balance"), 2) }}</span>
                </td>
            </tr>
        </tbody>
    </table>

    @foreach ($data as $item) 
    @php
        $classPending = $item->total_balance > 0? "span-pending" : "span-success";
    @endphp 
        <div style="border-top: 1px solid rgb(158, 156, 156); margin-top: 1rem;">

        <table cellspacing="0" class="col-10 mt10 account-status">
            <thead>
                <tr> 
                    <td class="align-center fcw cell-title" style="">
                        Cliente
                    </td>
                    <td colspan="4" class="align-center fcw">
                        {{ $item->name }}
                    </td>
                    <td class="align-center fcw cell-title">
                        Total Crédito
                    </td>
                    <td class="align-center fcw">
                        {{ currency() }} {{ number_format($item->total, 2) }}
                    </td>
                </tr>
                <tr> 
                    <td class="align-center fcw cell-title">
                        Cédula
                    </td>
                    <td colspan="4" class="align-center fcw">
                        {{ $item->dni }}
                    </td>
                    <td class="align-center fcw cell-title">
                        Total Pagado
                    </td>
                    <td class="align-center fcw">
                        {{ currency() }} {{ number_format($item->total_paid, 2) }}
                    </td>
                </tr>
                <tr> 
                    <td class="align-center fcw cell-title">
                        Contacto
                    </td>
                    <td colspan="4" class="align-center fcw">
                        @if ($item->phone)
                            <span>{{ $item->phone }}</span>
                        @endif
                        @if ($item->phone && $item->email)
                            <span>,</span>
                        @endif
                        @if ($item->email)
                            <span>{{ $item->email }}</span>
                        @endif
                    </td>
                    <td class="align-center fcw cell-title">
                        Total saldo
                    </td>
                    <td class="align-center fcw">
                        <span class="{{ $classPending }}">
                            {{ currency() }} {{ number_format($item->total_balance, 2) }}
                        </span>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr> 
                    <td class="align-center fcw cell-title">
                        Fecha
                    </td>
                    <td class="align-center fcw cell-title">
                        Concepto
                    </td>
                    <td class="align-center fcw cell-title">
                        Descripción
                    </td>
                    <td class="align-center fcw cell-title">
                        Inicial
                    </td>
                    <td class="align-center fcw cell-title">
                        Pago
                    </td>
                    <td class="align-center fcw cell-title">
                        Saldo
                    </td>
                    <td class="align-center fcw cell-title">
                        Estado
                    </td>
                </tr>
                @foreach ($item->credits as $credit)
                    @php
                        $classStatus = $item->status == 0? "span-pending" : "span-success";
                    @endphp 
                    <tr class="strong">
                        <td class="align-center">
                            {{ $credit->created_at->format("d/m/Y") }}
                        </td>
                        <td class="align-center">
                            Inmueble - {{ $credit->furniture->name }}
                        </td>
                        <td class="align-center">
                            Crédito
                        </td>
                        <td class="align-center">
                            {{ currency() }} {{ number_format($credit->furniture->initial_price, 2) }}
                        </td>
                        <td class="align-center">
                            -
                        </td>
                        <td class="align-center">
                            <span class="span-pending">
                                ({{ $credit->interest_percentage }}% de Interés) <br>
                            </span>
                          {{ currency() }} {{ number_format($credit->total, 2) }} 
                        </td>
                        <td class="align-center">
                            <span class="{{ $classStatus }}">
                                {{ $credit->status_text }}
                            </span>
                        </td>
                    </tr>

                    @foreach ($credit->payments as $payment)
                        <tr>
                            <td class="align-center">
                                {{ $payment->created_at->format("d/m/Y") }}
                            </td>
                            <td class="align-center">
                                {{ $payment->credit_cuote->number_letter }}
                            </td>
                            <td class="align-center">
                                {{ $payment->payment_method->name ?? '' }}
                            </td>
                            <td class="align-center">
                                -
                            </td>
                            <td class="align-center">
                                {{ currency() }} {{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="align-center">
                                {{ currency() }} {{ number_format($payment->remaining_balance, 2) }}
                            </td>
                            <td class="align-center">
                                -
                            </td>
                        </tr>
                    @endforeach
                    
                @endforeach
            </tbody>
        </table>
    @endforeach

</body>

</html>