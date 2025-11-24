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



    <div class="ticket centrado">
        {{-- <img src="{{ $base64 }}" width="145" height="auto" /> --}}
        <h1>PC SERVICIOS, TECNOLOGÍAS EN COMPUTACIÓN</h1>
        <h2>AUHA8412PQ3</h2>
        <h2>RAYÓN 815, CENTRO, OAXACA DE JUAREZ</h2>
        <h2>TEL:(951) 589-2000</h2>
        <h2>TEL:(951) 228-8850</h2>
        <h1>== TICKET DE VENTA ==</h1>

        {{-- Datos generales --}}
        <p><strong>Cliente:</strong> {{ $venta->cliente->full_name ?? 'CLIENTE PÚBLICO' }}</p>
        <p><strong>Folio:</strong> {{ $venta->folio }}</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</p>



        <table border="0">
            <thead>
                <tr>
                    <td class="producto" colspan="3">
                        ----------------------------------------------------------------------------------</td>
                </tr>
                <tr class="centrado">
                    <th class="cantidad">CANT</th>
                    <th class="producto">PU.</th>
                    <th class="precio">IMP.</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="producto" colspan="3">
                        ----------------------------------------------------------------------------------</td>
                </tr>
                @foreach ($venta->detalles as $detalle)
                    <tr>
                        <td class="producto uppercase" colspan="3">
                            @if ($detalle->producto)
                                {{ $detalle->producto->nombre }}
                            @else
                                {{ $detalle->producto_comun }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="cantidad">{{ $detalle->cantidad }}</td>
                        <td class="cantidad">{{ '$' . number_format($detalle->precio, 2, '.', ',') }}</td>
                        <td class="precio">{{ '$' . number_format($detalle->total, 2, '.', ',') }}</td>
                    </tr>

                    {{-- Mostrar devoluciones si existen --}}
                    @foreach ($detalle->devoluciones as $dev)
                        <tr>
                            <td colspan="3" class="producto">
                                DEVUELTO: {{ $dev->cantidad }}
                            </td>
                        </tr>
                        @if ($dev->notaCredito)
                            <tr>
                                <td colspan="3" class="producto">
                                    @if ($dev->notaCredito)
                                        Nota Crédito: ${{ number_format($dev->notaCredito->monto, 2, '.', ',') }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    <tr>
                        <td class="producto" colspan="3">
                            ----------------------------------------------------------------------------------</ </td>
                    </tr>
                @endforeach

                {{-- Aquí insertamos las notas de crédito generadas en esta venta --}}
                @foreach ($venta->notaCreditos as $nota)
                    <tr>
                        <td colspan="3" class="producto">
                            Nota Crédito Generada: {{ $nota->folio }}
                            por ${{ number_format($nota->monto, 2, '.', ',') }}
                        </td>
                    </tr>

                    @if ($nota->ventasAplicadas->count() > 0)
                        @foreach ($nota->ventasAplicadas as $ventaAplicada)
                            <tr>
                                <td></td>
                                <td colspan="2" class="producto">
                                    Aplicada en Venta: {{ $ventaAplicada->folio }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td></td>
                            <td colspan="2" class="producto">
                                (Pendiente de aplicar)
                            </td>
                        </tr>
                    @endif
                @endforeach

            </tbody>

            {{--
            <tr>
                <td class="cantidad"></td>
                <td class="producto">
                    <strong>TOTAL</strong>
                </td>
                <td class="precio">
                    ${{ number_format($venta->total, 2) }}
                </td>
            </tr>

            @if ($montoNotasCredito > 0)
                <tr>
                    <td class="cantidad"></td>
                    <td class="producto">
                        <strong>NOTA(S) DE CRÉDITO</strong>
                    </td>
                    <td class="precio">
                        - ${{ number_format($montoNotasCredito, 2) }}
                    </td>
                </tr>
                <tr>
                    <td class="cantidad"></td>
                    <td class="producto">
                        <strong>TOTAL NETO</strong>
                    </td>
                    <td class="precio">
                        ${{ number_format($totalNeto, 2) }}
                    </td>
                </tr>
            @endif
            <tr>
                <td class="cantidad"></td>
                <td class="producto">
                    <strong>TOTAL PAGADO</strong>
                </td>
                <td class="precio">
                    ${{ number_format($totalPagadoAjustado, 2) }}
                </td>
            </tr>

            <tr>
                <td class="cantidad"></td>
                <td class="producto">
                    <strong>FALTANTE</strong>
                </td>
                <td class="precio">
                    ${{ number_format(max($totalNeto - $totalPagadoAjustado, 0), 2) }}
                </td>
            </tr>
            --}}

            @if (!$venta->activo)
                <tr>
                    <td colspan="3" class="producto centrado">
                        *** VENTA CANCELADA ***
                    </td>
                </tr>

                @if ($venta->notaCreditoAsociada() && $venta->notaCreditoAsociada()->estado === 'PENDIENTE')
                    <tr>
                        <td colspan="3" class="producto">
                            {{ $venta->notaCreditoAsociada()->folio }}  {{ $venta->notaCreditoAsociada()->estado }}
                        </td>
                    </tr>
                @endif

                <tr>
                    <td class="producto" colspan="3">
                        ----------------------------------------------------------------------------------</ </td>
                </tr>
            @endif

            <tr>
                <td class="cantidad"></td>
                <td class="producto"><strong>TOTAL</strong></td>
                <td class="precio">
                    ${{ number_format($venta->total, 2, '.', ',') }}
                </td>
            </tr>

            {{-- FORMAS DE PAGO --}}
            <tr>
                <td class="producto" colspan="3">
                    ----------------------------------------------------------------------------------
                </td>
            </tr>
            <tr>
                <td colspan="3" class="centrado">
                    <strong>FORMAS DE PAGO</strong>
                </td>
            </tr>

            @foreach ($venta->pagos as $pago)
                <tr>
                    <td class="cantidad"></td>
                    <td class="producto">
                        {{ strtoupper($pago->metodo) }}
                    </td>
                    <td class="precio">
                        ${{ number_format($pago->monto, 2, '.', ',') }}
                    </td>
                </tr>

                {{-- Si el pago viene de una Nota de crédito --}}
                @if ($pago->pagable_type === App\Models\NotaCredito::class)
                    <tr>
                        <td></td>
                        <td colspan="2" class="producto">
                            Nota Crédito Folio: {{ $pago->pagable->folio ?? '' }}
                        </td>
                    </tr>
                @endif

                {{-- Si el pago viene de un Anticipo-apartado --}}
                @if ($pago->pagable_type === App\Models\AnticipoApartado::class)
                    <tr>
                        <td></td>
                        <td colspan="2" class="producto">
                            Anticipo Folio: {{ $pago->pagable->folio ?? '' }}
                        </td>
                    </tr>
                @endif
            @endforeach

            {{-- TOTAL PAGADO --}}
            <tr>
                <td class="cantidad"></td>
                <td class="producto"><strong>TOTAL PAGADO</strong></td>
                <td class="precio">
                    ${{ number_format($totalPagadoAjustado, 2, '.', ',') }}
                </td>
            </tr>

            {{-- FALTANTE --}}
            <tr>
                <td class="cantidad"></td>
                <td class="producto"><strong>FALTANTE</strong></td>
                <td class="precio">
                    ${{ number_format(max($totalNeto - $totalPagadoAjustado, 0), 2, '.', ',') }}
                </td>
            </tr>

            {{-- CAMBIO --}}
            <tr>
                <td class="cantidad"></td>
                <td class="producto"><strong>CAMBIO</strong></td>
                <td class="precio">
                    ${{ number_format($venta->cambio, 2, '.', ',') }}
                </td>
            </tr>


        </table>
    </div>
</body>

</html>
