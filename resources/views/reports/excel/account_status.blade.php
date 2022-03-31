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
        <tr>
            <td class="width-15">
                
                <img src="{{ public_path() . "/storage/" . config("app.logo_white") }}" width="120">

            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center">
                <b style="font-size: 15px;">Estados de Cuenta</b>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Total Créditos: </strong> 
            </td>
            <td colspan="6">
                <span>{{ currency() }} {{ number_format($data->sum("total"), 2) }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Total Pagado: </strong> 
            </td>
            <td colspan="6">
                <span>{{ currency() }} {{ number_format($data->sum("total_paid"), 2) }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Total Deuda: </strong> 
            </td>
            <td colspan="6">
                <span>{{ currency() }} {{ number_format($data->sum("total_balance"), 2) }}</span>
            </td>
        </tr>
    </table>

    @foreach ($data as $item) 
        @php
            $classPending = $item->total_balance > 0? "span-pending" : "span-success";
        @endphp 

        <table cellspacing="0" class="col-10 mt10 account-status">
                <tr> 
                    <td style="text-align: center" class="fcw cell-title">
                        <b>Cliente</b>
                    </td>
                    <td colspan="4" style="text-align: center" class="fcw">
                        {{ $item->name }}
                    </td>
                    <td style="text-align: center" class="fcw cell-title">
                        <b>Total Crédito</b>
                    </td>
                    <td style="text-align: center" class="fcw">
                        {{ number_format($item->total, 2) }}
                    </td>
                </tr>
                <tr> 
                    <td style="text-align: center" class="fcw cell-title">
                        <b>Cédula</b>
                    </td>
                    <td colspan="4" style="text-align: center" class="fcw">
                        {{ $item->dni }}
                    </td>
                    <td style="text-align: center" class="fcw cell-title">
                        <b>Total Pagado</b>
                    </td>
                    <td style="text-align: center" class="fcw">
                        {{ number_format($item->total_paid, 2) }}
                    </td>
                </tr>
                <tr> 
                    <td style="text-align: center" class="fcw cell-title">
                        <b>Contacto</b>
                    </td>
                    <td colspan="4" style="text-align: center" class="fcw">
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
                    <td style="text-align: center" class="fcw cell-title">
                        <b>Total saldo</b>
                    </td>
                    <td style="text-align: center" class="fcw">
                        <span class="{{ $classPending }}">
                            {{ number_format($item->total_balance, 2) }}
                        </span>
                    </td>
                </tr>
 
                <tr> 
                    <td style="text-align: center" class="fcw cell-title">
                        <b>Fecha</b>
                    </td>
                    <td style="text-align: center" class="fcw cell-title">
                        <b>Concepto</b>
                    </td>
                    <td style="text-align: center" class="fcw cell-title">
                        <b>Descripción</b>
                    </td>
                    <td style="text-align: center" class="fcw cell-title">
                        <b>Inicial</b>
                    </td>
                    <td style="text-align: center" class="fcw cell-title">
                        <b>Pago</b>
                    </td>
                    <td style="text-align: center" class="fcw cell-title">
                        <b>Saldo</b>
                    </td>
                    <td style="text-align: center" class="fcw cell-title">
                        <b>Estado</b>
                    </td>
                </tr>
                @foreach ($item->credits as $credit)
                    @php
                        $classStatus = $item->status == 0? "span-pending" : "span-success";
                    @endphp 
                    <tr class="strong">
                        <td style="text-align: center">
                            {{ $credit->created_at->format("d/m/Y") }}
                        </td>
                        <td style="text-align: center">
                            Inmueble - {{ $credit->furniture->name }}
                        </td>
                        <td style="text-align: center">
                            Crédito
                        </td>
                        <td style="text-align: center">
                            {{ number_format($credit->furniture->initial_price, 2) }}
                        </td>
                        <td style="text-align: center">
                            
                        </td>
                        <td style="text-align: center">
                          {{ number_format($credit->total, 2) }} 
                        </td>
                        <td style="text-align: center">
                            <span class="{{ $classStatus }}">
                                {{ $credit->status_text }}
                            </span>
                        </td>
                    </tr>

                    @foreach ($credit->payments as $payment)
                        <tr>
                            <td style="text-align: center">
                                {{ $payment->created_at->format("d/m/Y") }}
                            </td>
                            <td style="text-align: center">
                                Letra {{ $payment->credit_cuote->number_letter }}
                            </td>
                            <td style="text-align: center">
                                {{ $payment->payment_method->name ?? '' }}
                            </td>
                            <td style="text-align: center">
                                -
                            </td>
                            <td style="text-align: center">
                                {{ number_format($payment->amount, 2) }}
                            </td>
                            <td style="text-align: center">
                                {{ number_format($payment->remaining_balance, 2) }}
                            </td>
                            <td style="text-align: center">
                                
                            </td>
                        </tr>
                    @endforeach
                    
                @endforeach
        </table>
    @endforeach

</body>

</html>