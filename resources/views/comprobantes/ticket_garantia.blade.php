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
            position: relative; /* ← Agregar esto */
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
        <h1>== GARANTÍA ==</h1>


        {{-- Datos generales --}}
        <p><strong>Cliente:</strong> {{ $garantia->cliente->full_name ?? 'CLIENTE PÚBLICO' }}</p>
        <p><strong>Folio:</strong> {{ $garantia->folio }}</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($garantia->fecha)->format('d/m/Y H:i') }}</p>
        <p><strong>Estatus:</strong> {{ strtoupper($garantia->estatus) }}</p>
        {{--@if($venta->activo == 0)
           <strong>CANCELADA</strong>
        @endif
        --}}

        <table border="0" style="width:100%">
            <thead>
                <tr><td  class="producto" colspan="3">----------------------------------------------------------------------------------</td></tr>
                <tr class="centrado">
                    <th class="cantidad">CANT</th>
                    <th class="producto">PU.</th>
                    <th class="precio">IMP.</th>
                </tr>
            </thead>
            <tbody>
                <tr><td class="producto" colspan="3">----------------------------------------------------------------------------------</td></tr>
                <tr>
                    <td class="producto uppercase" colspan="3">
                        {{ $garantia->producto->nombre ?? $garantia->producto_personalizado }}
                    </td>
                </tr>
                <tr>
                    <td class="cantidad">{{ $garantia->cantidad }}</td>
                    <td class="cantidad">{{ '$' . number_format($garantia->precio_producto, 2, '.', ',') }}</td>
                    <td class="precio">{{ '$' . number_format($garantia->importe, 2, '.', ',') }}</td>
                </tr>
            </tbody>
        </table>

        <p><strong>Falla reportada:</strong> {{ $garantia->descripcion_fallo }}</p>
            @if($garantia->informacion_adicional)
                <p><strong>Información adicional:</strong> {{ $garantia->informacion_adicional }}</p>
            @endif

            {{-- Mostrar solución si existe --}}
            @if($garantia->solucion)
                <p class="producto"> ---------------------------------------------------------------------------------</p>
                <h3>Resultado de Garantía</h3>
                @if($garantia->solucion === 'Cambio físico')
                    <p><strong>Solución:</strong> Cambio físico realizado</p>
                    <p>Se entregó un producto nuevo en sustitución.</p>
                @elseif($garantia->solucion === 'No procede')
                    <p><strong>Solución:</strong> No procede</p>
                    <p>El producto funciona correctamente. No se realizaron cambios.</p>
                @elseif($garantia->solucion === 'Nota de crédito')
                    <p><strong>Solución:</strong> Nota de crédito</p>
                    <p>Monto otorgado: <strong>${{ number_format($garantia->importe, 2, '.', ',') }}</strong></p>
                    <p>Folio Nota: {{ $garantia->notaCreditos->last()->folio ?? '---' }}</p>
                @endif
                @if($garantia->nota_solucion)
                    <p><strong>Observaciones:</strong> {{ $garantia->nota_solucion }}</p>
                @endif
            @endif



    </div>
</body>

</html>
