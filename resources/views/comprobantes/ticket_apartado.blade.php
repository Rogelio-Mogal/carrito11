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
        <h1>== APARTADO ==</h1>

        <table border="0" width="100%" cellpadding="2" cellspacing="0">
            {{-- Datos generales --}}
            <tr>
                <td colspan="3"><strong>Cliente:</strong> {{ $anticipo->cliente->full_name ?? 'CLIENTE PÚBLICO' }}
                </td>
            </tr>
            <tr>
                <td colspan="3"><strong>Folio:</strong> {{ $anticipo->folio }}</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Fecha:</strong>
                    {{ \Carbon\Carbon::parse($anticipo->fecha)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Estatus:</strong> {{ strtoupper($anticipo->estatus) }}</td>
            </tr>

            {{-- Encabezado productos --}}
            <tr>
                <th>CANT</th>
                <th>PRODUCTO</th>
                <th>TOTAL</th>
            </tr>

            {{-- Productos --}}
            @foreach ($anticipo->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ $detalle->producto->nombre ?? ($detalle->producto_comun ?? 'Producto') }}</td>
                    <td>{{ '$' . number_format($detalle->total, 2) }}</td>
                </tr>
            @endforeach

            {{-- Separador --}}
            <tr>
                <td colspan="3">----------------------------------------------------------------------------------</td>
            </tr>

            {{-- Abonos --}}
            <tr>
                <th colspan="3">Abonos realizados</th>
            </tr>
            <tr>
                <th>Fecha</th>
                <th>Referencia</th>
                <th>Monto</th>
            </tr>
            @foreach ($anticipo->abonos as $abono)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($abono->fecha)->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $abono->referencia ?? '-' }}</td>
                    <td>{{ '$' . number_format($abono->monto, 2) }}</td>
                </tr>
            @endforeach

            {{-- Totales --}}
            <tr>
                <td colspan="2"><strong>Total del anticipo</strong></td>
                <td>{{ '$' . number_format($anticipo->total, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Monto abonado</strong></td>
                <td>{{ '$' . number_format($anticipo->abona, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Monto pendiente</strong></td>
                <td>{{ '$' . number_format($anticipo->debia, 2) }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
