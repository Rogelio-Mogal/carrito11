<?php
if ($userPrinterSize == '58') {
    $medidaTicket = 170; //180;685 para el de 58mm, 945 para el de 80mm
} elseif ($userPrinterSize == '80') {
    $medidaTicket = 270; //180;685 para el de 58mm, 945 para el de 80mm
}
?>
<!DOCTYPE html>
<html>

<head>

    <style>
        * {
            font-size: 9px;
            font-family: 'DejaVu Sans', serif;
        }

        h1 {
            font-size: 9px;
        }

        h2 {
            font-size: 9px;
        }

        .ticket {
            margin: 2px;
        }

        td,
        th,
        tr,
        table {
            border-top: 0px solid black;
            border-collapse: collapse;
            margin: 0 auto;
            margin-left: 6px;
            margin-right: 6px;
        }

        td.precio {
            text-align: right;
            font-size: 9px;
        }

        td.cantidad {
            font-size: 9px;
        }

        td.producto {
            text-align: left;
        }

        th {
            text-align: center;
        }


        .centrado {
            margin-top: 10px;
            text-align: center;
            align-content: center;
        }

        .textoGrande {
            font-size: 13px;
            font-weight: bold;
        }

        .ticket {
            width: <?php echo $medidaTicket; ?>px;
            max-width: <?php echo $medidaTicket; ?>px;
        }

        img {
            /*max-width: inherit;
            width: inherit;*/
        }

        * {
            margin: 0;
            padding: 0;
        }


        .ticket {
            position: relative;
            /* ← Agregar esto */
            width: <?php echo $medidaTicket; ?>px;
            max-width: <?php echo $medidaTicket; ?>px;
            margin: 0;
            padding: 0;
        }

        body {
            text-align: center;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .cancelada {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 40px;
            color: rgba(255, 0, 0, 0.2);
            font-weight: bold;
            z-index: 9999;
            pointer-events: none;
            white-space: nowrap;
        }
    </style>
</head>

<body>

@php
    $montoMostrado = 0;
@endphp

    <div class="ticket centrado">
        {{-- <img src="{{ $base64 }}" width="145" height="auto" /> --}}
        <h1>PC SERVICIOS, TECNOLOGÍAS EN COMPUTACIÓN</h1>
        <h2>AUHA8412PQ3</h2>
        <h2>RAYÓN 815, CENTRO, OAXACA DE JUAREZ</h2>
        <h2>TEL:(951) 589-2000</h2>
        <h2>TEL:(951) 228-8850</h2>
        <h1>== ABONO ==</h1>

        <table border="0" width="100%" cellpadding="2" cellspacing="0">
            {{-- Datos generales --}}
            <tr>
                <td colspan="3"><strong>Folio:</strong> {{ $abono->folio }}</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Cliente:</strong> {{ $abono->cliente->full_name ?? 'CLIENTE PÚBLICO' }}</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Fecha:</strong>
                    {{ \Carbon\Carbon::parse($abono->fecha)->format('d/m/Y H:i:s') }}</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Monto total:</strong> ${{ number_format($abono->monto, 2) }}</td>
            </tr>

            {{-- Separador --}}
            <tr>
                <td colspan="3">----------------------------------------------------------------------------------
                </td>
            </tr>

            {{-- Encabezado formas de pago --}}
            <tr>
                <th colspan="3">Formas de pago</th>
            </tr>
            <tr>
                <th align="center">Método</th>
                <th colspan="2" align="center">Monto</th>
            </tr>

            {{-- Pagos directamente del abono --}}
            @foreach ($abono->pagos as $pago)
                @php
                    $montoMostrado += $pago->monto;
                @endphp
                <tr>
                    <td align="center">{{ strtoupper($pago->metodo) }}</td>
                    <td colspan="2" align="center">${{ number_format($pago->monto, 2) }}</td>
                </tr>
            @endforeach

            {{-- Pagos de ventas o anticipos relacionados--}}
            @foreach ($abono->detalles as $detalle)
                @if ($detalle->abonado_a && $detalle->abonado_a->pagos)
                    @foreach ($detalle->abonado_a->pagos as $pago)
                        @php
                            // Verifica si ya no se excede del monto total
                            if ($montoMostrado >= $abono->monto) {
                                continue 2; // Salta al siguiente detalle
                            }

                            // Calcula monto a mostrar para no exceder
                            $montoFaltante = $abono->monto - $montoMostrado;
                            $montoMostrar = min($pago->monto, $montoFaltante);
                            $montoMostrado += $montoMostrar;
                        @endphp
                        <tr>
                            <td align="center">{{ strtoupper($pago->metodo) }}</td>
                            <td colspan="2" align="center">${{ number_format($montoMostrar, 2) }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
    
         

            {{-- Separador --}}
            <tr>
                <td colspan="3">----------------------------------------------------------------------------------
                </td>
            </tr>

            {{-- Encabezado aplicado a --}}
            <tr>
                <th colspan="3">Aplicado a</th>
            </tr>
            <tr>
                <th align="center">Tipo</th>
                <th align="center">Folio</th>
                <th align="center">Monto</th>
            </tr>
            @foreach ($abono->detalles as $detalle)
                <tr>
                    <td align="center">
                        @if ($detalle->abonado_a_type === App\Models\VentaCredito::class)
                            Venta a crédito
                        @elseif($detalle->abonado_a_type === App\Models\AnticipoApartado::class)
                            {{ ucfirst($detalle->abonado_a->tipo) ?? 'Anticipo/Apartado' }}
                        @elseif($detalle->abonado_a_type === App\Models\Venta::class)
                            Venta
                        @else
                            Otro
                        @endif
                    </td>
                    <td align="center">
                        @if ($detalle->abonado_a_type === App\Models\VentaCredito::class)
                            {{ $detalle->abonado_a->venta->folio ?? '-' }}
                        @else
                            {{ $detalle->abonado_a->folio ?? '-' }}
                        @endif
                    </td>
                    <td align="center">${{ number_format($detalle->abonado, 2) }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</body>

</html>
