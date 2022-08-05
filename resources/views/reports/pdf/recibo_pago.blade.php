<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ public_path('css/pdf-reports.css') }}">

    <title>Recibo</title>

    <style>
        @font-face {
            font-family: 'Anton';
            src: url({{ storage_path('fonts/Anton-Regular.ttf') }}) format("truetype");
        }
    </style>
    
</head>

<body class="recibo">

    <div>
        <div style="float: left;">
            <div class="titulo-empresa">
                {{ config("app.business_name") }}
            </div>
            <div class="bold info-empresa">
                RNC: {{ config("app.business_identify") }}
            </div>
            <div class="info-empresa">
                {{ config("app.business_address") }}.
            </div>
            <div class="info-empresa">
                {{ config("app.city_name") }}, {{ config("app.country_name") }}.
            </div>
            <div class="info-empresa">
                TEL: {{ config("app.business_phone") }}.
            </div>
        </div>
        <div style="float: right">
            <table class="mb-2">
                <tr>
                    <td></td>
                    <td class="titulo-recibo">
                        RECIBO DE CAJA
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td style="line-height: 1rem">&nbsp;</td>
                </tr>
                <tr>
                    <td class="campos-recibo">Número de Recibo:</td>
                    <td class="line value-recibo">
                        {{ $payment->id }}
                    </td>
                </tr>
                <tr>
                    <td class="campos-recibo">Número de NFC:</td>
                    <td class="line value-recibo">
                        {{ $payment->nfc ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td class="campos-recibo">Fecha:</td>
                    <td class="line value-recibo">
                        {{ $payment->created_at->isoFormat("LL") }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div style="border-top: 5px solid #646464"></div>
    <table cellspacing="0" class="col-10 table-conceptos">
        <tr>
            <td style="width: 10%" class="line concepto">Propietario:</td>
            <td style="width: 45%" class="line">
                {{ $payment->credit_cuote->credit->furniture->customer->name ?? '' }}
            </td>
            <td style="width: 12%" class="line concepto">Solar No.:</td>
            <td style="width: 30%"class="line">
                
            </td>
        </tr>
    </table>
    <table cellspacing="0" class="col-10 table-conceptos">
        <tr>
            <td style="width: 22%" class="line concepto">Hemos Recibido de:</td>
            <td class="line">
                {{ $payment->credit_cuote->credit->furniture->customer->name ?? '' }}
            </td>
        </tr>
    </table>
    <table cellspacing="0" class="col-10 table-conceptos">
        <tr>
            <td style="width: 17%" class="line concepto">La Cantidad de:</td>
            <td class="line">
                {{  strtoupper(number_words($payment->total, ($payment->currency->name ?? "Pesos"), ",", ".")) }}
            </td>
        </tr>
    </table>
    <table cellspacing="0" class="col-10 table-conceptos">
        <tr>
            <td style="width: 55%" class="line"></td>
            <td style="width: 7%" class="line concepto">{{ ($payment->currency->symbol ?? "$") }}:</td>
            <td style="" class="line">
                {{ number_format($payment->total) }}
            </td>
        </tr>
    </table>
    <table cellspacing="0" class="col-10 table-conceptos">
        <tr>
            <td style="width: 19%" class="line concepto">Por Concepto de:</td>
            <td class="line">
                Pago de Cuota {{ $payment->credit_cuote->number_letter }}
            </td>
        </tr>
    </table>
    <table cellspacing="0" class="col-10 table-conceptos">
        <tr>
            <td style="width: 17%" class="line concepto">Forma de Pago:</td>
            <td class="line">
                {{ $payment->payment_method->name ?? '' }}
            </td>
        </tr>
    </table>
    <table cellspacing="0" class="col-10 table-conceptos">
        <tr>
            <td style="width: 13%" class="line concepto">Comentario:</td>
            <td class="line">
                {{ $payment->note }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="100%" class="line">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" style="100%" class="line">&nbsp;</td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <br>
    <br>
    <table cellspacing="0" class="col-10 table-conceptos">
        <tr>
            <td style="width: 60%" class="line concepto">&nbsp;</td>
            <td style="width: 30%; text-align: center; font-size: 13px; position: relative;" class="line">
                {{ config("app.receipt_name") }}
                <img src="{{ public_path('sello.png') }}" width="40%" alt="" style="position: absolute; top: -40px; opacity: 0.6">
            </td>
            <td style="width: 10%" class="line concepto">&nbsp;</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center">Administración</td>
            <td></td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    @if (config("app.receipt_footer"))        
        <div style="font-size: 13px">
            <b>Información Adicional: </b> {{ config("app.receipt_footer") }}
        </div>
    @endif
</body>

</html>