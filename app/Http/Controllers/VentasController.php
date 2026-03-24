<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\AnticipoApartado;
use App\Models\CajaMovimiento;
use App\Models\CajaTurno;
use App\Models\DetalleAbono;
use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\NotaCredito;
use App\Models\Producto;
use App\Models\ProductoNumeroSerie;
use App\Models\Reparacion;
use App\Models\TipoPago;
use App\Models\Venta;
use App\Models\VentaCredito;
use App\Models\VentaDetalle;
use App\Models\VentaDevoluciones;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;


class VentasController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ventas.ver')
        ->only(['index', 'show']);

        $this->middleware('permission:ventas.crear')
            ->only(['create', 'store']);

        $this->middleware('permission:ventas.editar')
            ->only(['edit', 'update']);

        $this->middleware('permission:ventas.eliminar')
            ->only(['destroy']);
    }

    public function index()
    {
        return view('ventas.index');
    }

    public function create(Request $request)
    {
        /*$turnoAbierto = CajaTurno::where('estado', 'abierto')->where('usuario_id', auth()->id())->first();
        if (!$turnoAbierto) {
            return redirect()->route('admin.caja.turno.create')->with('warning', 'Debes abrir caja antes de registrar ventas.');
        }

        // 🧮 Calcular efectivo acumulado en el turno abierto
        $fechaInicio = now()->startOfDay(); //$turnoAbierto->fecha_apertura;
        $fechaFin = now();

        // 1️⃣ Ventas en efectivo
        $ventasEfectivo = TipoPago::where('metodo', 'Efectivo')
            ->where('activo', 1)
            ->whereHasMorph(
                'pagable',
                [Venta::class],
                fn($q) => $q->where('wci', auth()->id())
                            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            )
            ->sum('monto');

        // 2️⃣ Abonos de ventas a crédito
        $abonosVentas = TipoPago::where('metodo', 'Efectivo')
            ->where('activo', 1)
            ->whereHasMorph(
                'pagable',
                [VentaCredito::class],
                fn($q) => $q->where('wci', auth()->id())
                            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            )
            ->sum('monto');

        // 3️⃣ Abonos de anticipos y apartados
        $abonosAnticipos = TipoPago::where('metodo', 'Efectivo')
            ->where('activo', 1)
            ->whereHasMorph(
                'pagable',
                [AnticipoApartado::class],
                fn($q) => $q->where('wci', auth()->id())
                            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            )
            ->sum('monto');

        $efectivoOperaciones = $ventasEfectivo + $abonosVentas + $abonosAnticipos;

        // 4️⃣ Movimientos manuales de caja
        $entradas = CajaMovimiento::where('tipo', 'entrada')
            ->where('activo', 1)
            ->where('usuario_id', auth()->id())
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');

        $salidas = CajaMovimiento::where('tipo', 'salida')
            ->where('activo', 1)
            ->where('usuario_id', auth()->id())
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');

        // ✅ Total en caja (apertura + operaciones + entradas - salidas)
        $totalEfectivo = $turnoAbierto->efectivo_inicial  + $efectivoOperaciones + $entradas - $salidas;
        */
        $turnoAbierto = CajaTurno::where('estado', 'abierto')
            ->where('usuario_id', auth()->id())
            ->first();

        if (!$turnoAbierto) {
            // No hay turno abierto, usuario debe abrir uno
            return redirect()->route('admin.caja.turno.create')
                ->with('warning', 'Debes abrir caja antes de registrar ventas.');
        }

        // 1️⃣ Verificar si el turno es de días anteriores
        $fechaTurno = $turnoAbierto->fecha_apertura->toDateString();
        $hoy = now()->toDateString();

        if ($fechaTurno < $hoy) {
            // 2️⃣ Calcular efectivo acumulado hasta hoy
            //$fechaInicio = $turnoAbierto->fecha_apertura;

            // 🧮 Calcular efectivo acumulado en el turno abierto
            if ($turnoAbierto->fecha_apertura->toDateString() < now()->toDateString()) {
                // Turno antiguo: calcular desde la apertura del turno
                $fechaInicio = $turnoAbierto->fecha_apertura;
            } else {
                // Turno del día: calcular solo desde hoy
                $fechaInicio = now()->startOfDay();
            }


            $fechaFin = now();

            $ventasEfectivo = TipoPago::where('metodo', 'Efectivo')
                ->where('activo', 1)
                ->whereHasMorph('pagable', [Venta::class], fn($q) => $q->where('wci', auth()->id())->whereBetween('fecha', [$fechaInicio, $fechaFin]))
                ->sum('monto');

            $abonosVentas = TipoPago::where('metodo', 'Efectivo')
                ->where('activo', 1)
                ->whereHasMorph('pagable', [VentaCredito::class], fn($q) => $q->where('wci', auth()->id())->whereBetween('created_at', [$fechaInicio, $fechaFin]))
                ->sum('monto');

            $abonosAnticipos = TipoPago::where('metodo', 'Efectivo')
                ->where('activo', 1)
                ->whereHasMorph('pagable', [AnticipoApartado::class], fn($q) => $q->where('wci', auth()->id())->whereBetween('created_at', [$fechaInicio, $fechaFin]))
                ->sum('monto');

            $entradas = CajaMovimiento::where('tipo', 'entrada')
                ->where('activo', 1)
                ->where('usuario_id', auth()->id())
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->sum('monto');

            $salidas = CajaMovimiento::where('tipo', 'salida')
                ->where('activo', 1)
                ->where('usuario_id', auth()->id())
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->sum('monto');

            $efectivoCalculado = $turnoAbierto->efectivo_inicial + $ventasEfectivo + $abonosVentas + $abonosAnticipos + $entradas - $salidas;

            // 3️⃣ Redirigir a la vista de cierre de turno
            return redirect()->route('admin.caja.turno.create');
        }

        // 4️⃣ Turno del día actual, se puede continuar registrando ventas
        $fechaInicio = $turnoAbierto->fecha_apertura;
        $fechaFin = now();

        $ventasEfectivo = TipoPago::where('metodo', 'Efectivo')
            ->where('activo', 1)
            ->whereHasMorph('pagable', [Venta::class], fn($q) => $q->where('wci', auth()->id())->whereBetween('fecha', [$fechaInicio, $fechaFin]))
            ->sum('monto');

        $abonosVentas = TipoPago::where('metodo', 'Efectivo')
            ->where('activo', 1)
            ->whereHasMorph('pagable', [VentaCredito::class], fn($q) => $q->where('wci', auth()->id())->whereBetween('created_at', [$fechaInicio, $fechaFin]))
            ->sum('monto');

        $abonosAnticipos = TipoPago::where('metodo', 'Efectivo')
            ->where('activo', 1)
            ->whereHasMorph('pagable', [AnticipoApartado::class], fn($q) => $q->where('wci', auth()->id())->whereBetween('created_at', [$fechaInicio, $fechaFin]))
            ->sum('monto');

        $entradas = CajaMovimiento::where('tipo', 'entrada')
            ->where('activo', 1)
            ->where('usuario_id', auth()->id())
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');

        $salidas = CajaMovimiento::where('tipo', 'salida')
            ->where('activo', 1)
            ->where('usuario_id', auth()->id())
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');

        $totalEfectivo = $turnoAbierto->efectivo_inicial + $ventasEfectivo + $abonosVentas + $abonosAnticipos + $entradas - $salidas;

        //return view('ventas.create', compact('turnoAbierto', 'totalEfectivo'));


        $ventas = new Venta();
        $ventas->cliente_id = $request->query('cliente_id', 1);
        //$reparacion_id = session('reparacion_id', $request->query('reparacion_id'));
        $reparacion_id = $request->query('reparacion_id', session('reparacion_id', null));

        // Valores por defecto
        $nota_credito_ids = $request->query('nota_credito_ids');
        $nota_credito_monto = $request->query('nota_credito_monto', 0);
        $cliente_nombre = $request->query('cliente_nombre', 'CLIENTE PÚBLICO');
        $metodo = 'create';
        // Traer detalle vacío u inicializar
        $detalle = collect();

        $formasPago = [
            ['metodo' => '', 'monto' => '', 'referencia' => '']
        ];


        if ($reparacion_id) {
            $reparacion = Reparacion::with('productos.producto')->findOrFail($reparacion_id);

             // asignar cliente directamente desde la reparación
            if ($reparacion->cliente) {
                $ventas->cliente_id = $reparacion->cliente_id;
                $ventas->nombre_cliente = $reparacion->cliente->full_name;
            }

            // Convertimos productos de la reparación a formato detalle
            $detalle = $reparacion->productos->map(function ($p) {
                return [
                    'producto_id'   => $p->producto_id,
                    'name_producto' => $p->producto->nombre ?? 'SIN NOMBRE',
                    'cantidad'      => $p->cantidad,
                    'precio'        => $p->precio_unitario,
                    'total'         => $p->total,
                    'tipo_item'     => $p->producto->tipo ?? 'PRODUCTO',
                    'series'        => $p->series ?? '',
                ];
            });

            session()->forget('reparacion_id'); // 🔑 limpiar después de usar
        }



        // Verificar que la nota de crédito esté activa
        if ($nota_credito_ids) {
            $idsArray = explode(',', $nota_credito_ids);
            $notasActivas = NotaCredito::whereIn('id', $idsArray)
                ->where('activo', true)
                ->pluck('id')
                ->toArray();

            // Si alguna nota no está activa, redirigir al create normal
            if (count($notasActivas) !== count($idsArray)) {
                return redirect()->route('admin.ventas.create')->with('error', 'Alguna de las notas de crédito no está activa.');
            }
        }

        return view('ventas.create', compact(
            'ventas',
            'detalle',
            'nota_credito_ids',
            'nota_credito_monto',
            'cliente_nombre',
            'formasPago',
            'metodo',
            'reparacion_id',
            'turnoAbierto',
            'totalEfectivo'
        ));
    }

    public function store(Request $request)
    {
        // VALIDACIÓN: Solo uno permitido
        if (!empty($request->nota_credito_ids) && !empty($request->anticipo_apartado_ids)) {
            return back()->withErrors([
                'anticipo_o_nota' => 'Solo puedes aplicar una nota de crédito O un anticipo-apartado, no ambos.'
            ])->withInput($request->all());
        }

        try {
            DB::beginTransaction();

            // 1. VALIDAR STOCK ANTES DE CREAR LA VENTA
            foreach ($request->detalles as $detalle) {
                $productoId = $detalle['producto_id'] ?? null;
                $cantidadSolicitada = intval($detalle['cantidad']);

                if ($detalle['tipo_item'] === 'PRODUCTO' && $productoId) {
                    $producto = Producto::with('inventarioUsuario')->find($productoId);

                    if (!$producto) {
                        throw new \Exception("El producto con ID {$productoId} no existe.");
                    }

                    $stock = $producto->inventarioUsuario->cantidad ?? 0;

                    if ($cantidadSolicitada > $stock) {
                        session()->flash('swal', [
                            'icon' => "error",
                            'title' => "Operación fallida.",
                            'text' => "El producto {$producto->nombre} no tiene suficiente stock. Disponible: $stock",
                            'customClass' => [
                                'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                            ],
                            'buttonsStyling' => false
                        ]);
                        return redirect()->back()->withInput($request->all());
                    }
                }
            }

            // 2. GENERAR FOLIO
            $anioActual = now()->year;
            //$ultimoFolio = Venta::whereYear('created_at', $anioActual)
            //    ->orderByDesc('id')
            //    ->value('folio');

            $ultimoFolio = Venta::whereYear('created_at', $anioActual)
                ->lockForUpdate() // 🔒 bloquea filas de ventas de este año mientras corre la transacción
                ->orderByDesc('id')
                ->value('folio');

            $ultimoNumero = 0;
            if ($ultimoFolio && preg_match('/VENTA-(\d+)-' . $anioActual . '/', $ultimoFolio, $match)) {
                $ultimoNumero = intval($match[1]);
            }

            $nuevoNumero = $ultimoNumero + 1;
            //$folio = "VENTA-{$nuevoNumero}-{$anioActual}";
            $folio = sprintf("VENTA-%05d-%d", $nuevoNumero, $anioActual);

            // 3. CREAR VENTA
            $venta = new Venta();
            $venta->user_id = auth()->user()->id;
            $venta->cliente_id = $request->cliente_id;
            $venta->folio = $folio;
            $venta->fecha = now();
            $venta->total = $request->total_venta;
            $venta->monto_credito = floatval($request->monto_credito) ?: 0;
            $venta->monto_recibido = 0;
            $venta->cambio = $request->total_cambio;
            $venta->tipo_venta = $request->tipo_venta;
            $venta->save();

            // 4. FORMAS DE PAGO
            $montoRecibidoNetoTotal = 0;
            foreach ($request->formas_pago as $fp) {
                if (!empty($fp['monto']) && $fp['monto'] > 0) {
                    $monto_final = $fp['monto'];
                    if ($fp['metodo'] === 'Efectivo' && $venta->cambio > 0) {
                        $monto_final -= $venta->cambio;
                    }
                    TipoPago::create([
                        'pagable_id'   => $venta->id,
                        'pagable_type' => Venta::class,
                        'metodo'       => $fp['metodo'],
                        'monto'        => $monto_final,
                        'referencia'   => $fp['referencia'] ?? null,
                        'wci'          => auth()->id(),
                        'activo'       => true,
                    ]);
                    $montoRecibidoNetoTotal += $monto_final;
                }
            }

            // 4B. SI SE USÓ NOTA DE CRÉDITO
            /*if (!empty($request->nota_credito_ids)) {
                $idsNotas = explode(",", $request->nota_credito_ids);
                $montoNota = floatval($request->nota_credito_monto);

                // Guardar como forma de pago
                TipoPago::create([
                    'pagable_id'   => $venta->id,
                    'pagable_type' => Venta::class,
                    'metodo'       => 'Nota crédito',
                    'monto'        => $montoNota,
                    'referencia'   => "Notas: " . implode(",", $idsNotas),
                    'wci'          => auth()->id(),
                    'activo'       => true,
                ]);

                $montoRecibidoNetoTotal += $montoNota;

                // Marcar notas como usadas
                NotaCredito::whereIn('id', $idsNotas)->update([
                    'estado' => 'APLICADA',
                    'activo' => false
                ]);

                // Registrar en venta_devoluciones la venta donde se aplicó la nota
                foreach ($idsNotas as $notaId) {
                    $nota = NotaCredito::with('ventaDevoluciones')->find($notaId); // traer detalles de la nota

                    if ($nota) {
                        if ($nota->ventaDevoluciones->isNotEmpty()) {
                            // Devolución parcial por detalle
                            foreach ($nota->ventaDevoluciones  as $d) {
                                VentaDevoluciones::create([
                                    'venta_id'         => $nota->notable_id,
                                    'venta_detalle_id' => $d->venta_detalle_id,  // amarra al detalle específico
                                    'nota_credito_id'  => $nota->id,
                                    'venta_aplicada_id'=> $venta->id,
                                    'cantidad'         => $d->cantidad,
                                    'monto'            => $d->monto,
                                    'motivo'           => $d->motivo ?? $nota->motivo,
                                ]);
                            }
                        } else {
                            // Devolución total
                            VentaDevoluciones::create([
                                'venta_id'          => $nota->notable_id,
                                'venta_detalle_id'  => null,
                                'nota_credito_id'   => $nota->id,
                                'venta_aplicada_id' => $venta->id,
                                'cantidad'          => null,
                                'monto'             => $nota->monto,
                                'motivo'            => $nota->motivo ?? 'APLICADA EN VENTA',
                            ]);
                        }
                    }
                }
            }*/

            if ($request->filled('notas_credito')) {
                $totalNotas = 0;

                foreach ($request->notas_credito as $notaData) {
                    $nota = NotaCredito::with('ventaDevoluciones')->find($notaData['id']);

                    if ($nota) {
                        $monto = floatval($notaData['monto']);
                        $totalNotas += $monto;

                        // Registrar forma de pago individual
                        TipoPago::create([
                            'pagable_id'   => $venta->id,
                            'pagable_type' => Venta::class,
                            'metodo'       => 'Nota crédito',
                            'monto'        => $monto,
                            'referencia'   => "Nota: {$nota->id}",
                            'wci'          => auth()->id(),
                            'activo'       => true,
                        ]);

                        // Marcar la nota como aplicada
                        $nota->update([
                            'estado' => 'APLICADA',
                            'activo' => false
                        ]);

                        // Registrar relación en venta_devoluciones
                        if ($nota->ventaDevoluciones->isNotEmpty()) {
                            foreach ($nota->ventaDevoluciones as $d) {
                                VentaDevoluciones::create([
                                    'venta_id'          => $nota->notable_id,
                                    'venta_detalle_id'  => $d->venta_detalle_id,
                                    'nota_credito_id'   => $nota->id,
                                    'venta_aplicada_id' => $venta->id,
                                    'cantidad'          => $d->cantidad,
                                    'monto'             => $d->monto,
                                    'motivo'            => $d->motivo ?? $nota->motivo,
                                ]);
                            }
                        } else {
                            VentaDevoluciones::create([
                                'venta_id'          => $nota->notable_id,
                                'nota_credito_id'   => $nota->id,
                                'venta_aplicada_id' => $venta->id,
                                'monto'             => $nota->monto,
                                'motivo'            => $nota->motivo ?? 'APLICADA EN VENTA',
                            ]);
                        }
                    }
                }

                $montoRecibidoNetoTotal += $totalNotas;
            }

            $venta->monto_recibido = $montoRecibidoNetoTotal;
            $venta->save();

            // 5. DETALLES DE LA VENTA Y ACTUALIZAR INVENTARIO
            foreach ($request->detalles as $detalle) {
                VentaDetalle::create([
                    'venta_id'            => $venta->id,
                    'tipo_item'           => $detalle['tipo_item'] ?? null,
                    'producto_id'         => $detalle['producto_id'] ?? null,
                    'servicio_ponchado_id' => $detalle['servicio_ponchado_id'] ?? null,
                    'producto_comun'      => $detalle['producto_comun'] ?? null,
                    'cantidad'            => $detalle['cantidad'],
                    'precio'              => $detalle['precio'],
                    'total'               => $detalle['total'],
                    'activo'              => 1,
                ]);

                // Guardar números de serie si es producto y tiene series
                if ($detalle['tipo_item'] === 'PRODUCTO' && !empty($detalle['series'])) {
                    $series = explode('|', $detalle['series']);
                    foreach ($series as $serie) {
                        $serie = trim($serie);
                        if ($serie !== '') {
                            ProductoNumeroSerie::create([
                                'producto_id' => $detalle['producto_id'],
                                'venta_id'    => $venta->id,
                                'numero_serie' => $serie,
                                'disponible'  => 0, // ya se vendió
                                'proveedor_id' => 1, // id de proveedor genérico
                                'compra_id'   => 1,
                            ]);
                        }
                    }
                }

                //  Actualizar inventario y kardex
                if ($detalle['tipo_item'] == 'PRODUCTO') {
                    $inventario = Inventario::where('producto_id', $detalle['producto_id'])
                        ->where('sucursal_id', auth()->user()->sucursal_id)
                        ->first();

                    if ($inventario) {
                        $inventario->cantidad -= $detalle['cantidad'];
                        $inventario->updated_at = now();
                        $inventario->save();

                        $ultimoRegistro = Kardex::where('producto_id', $detalle['producto_id'])
                            ->orderBy('created_at', 'desc')
                            ->first();

                        $saldoActual = $ultimoRegistro ? $ultimoRegistro->saldo : 0;
                        $cantidadEntrada = 0;
                        $cantidadSalida  = $detalle['cantidad'];
                        $nuevoSaldo = $saldoActual - $cantidadSalida;

                        Kardex::create([
                            'sucursal_id'   => auth()->user()->sucursal_id,
                            'producto_id'   => $detalle['producto_id'],
                            'movimiento_id' => $venta->id,
                            'tipo_movimiento' => 'SALIDA',
                            'tipo_detalle'  => 'VENTA',
                            'fecha'         => now(),
                            'folio'         => $folio,
                            'debe'          => $cantidadEntrada,
                            'haber'         => $cantidadSalida,
                            'saldo'         => $nuevoSaldo,
                            'wci'           => auth()->id(),
                        ]);
                    }
                }
            }

            // 6. SI ES CRÉDITO
            if ($request->tipo_venta == 'CRÉDITO') {
                $montoCredito = floatval($request->monto_credito) ?: 0;

                $ventaCredito = VentaCredito::create([
                    'venta_id'     => $venta->id,
                    'cliente_id' => $venta->cliente_id,
                    'monto_credito' => $montoCredito,
                    'saldo_actual' => $montoCredito,
                    'liquidado'     => false,
                    'activo'        => true,
                ]);

                // Si hubo un pago inicial (ej. cliente deja un anticipo al momento de la venta)
                if ($montoRecibidoNetoTotal > 0) {

                    $anioActual = now()->year;

                    // Obtener el último folio de abonos de este año
                    $ultimoFolio = Abono::whereYear('created_at', $anioActual)
                        ->lockForUpdate() // bloquea filas de abonos mientras corre la transacción
                        ->orderByDesc('id')
                        ->value('folio');

                    $ultimoNumero = 0;
                    if ($ultimoFolio && preg_match('/ABONO-(\d+)-' . $anioActual . '/', $ultimoFolio, $match)) {
                        $ultimoNumero = intval($match[1]);
                    }

                    $nuevoNumero = $ultimoNumero + 1;
                    $folio = sprintf("ABONO-%05d-%d", $nuevoNumero, $anioActual);

                    // Registrar ABONO maestro
                    $abono = Abono::create([
                        'folio'               => $folio,
                        'fecha'               => now(),
                        'abonable_id'         => $venta->id,
                        'abonable_type'       => Venta::class,
                        'cliente_id'          => $venta->cliente_id,
                        'monto'               => $montoRecibidoNetoTotal,
                        'saldo_global_antes'  => $venta->total,
                        'saldo_global_despues' => $venta->total - $montoRecibidoNetoTotal,
                        'referencia'          => 'ABONO INICIAL',
                        'activo'              => true,
                        'wci'                 => auth()->id(),
                    ]);

                    // Registrar DETALLE del abono asociado a la venta a crédito
                    DetalleAbono::create([
                        'abono_id'         => $abono->id,
                        'venta_credito_id' => $ventaCredito->id, // ahora usamos la FK de venta_creditos
                        'abonado_a_id'     => $venta->id,
                        'abonado_a_type'   => Venta::class,
                        'monto_antes'      => $venta->total,
                        'abonado'          => $montoRecibidoNetoTotal,
                        'saldo_despues'    => $venta->total - $montoRecibidoNetoTotal,
                        'activo'           => true,
                    ]);
                }
            }

            // 7. SI SE APLICARON ANTICIPOS O APARTADOS
            if (!empty($request->anticipos) && is_array($request->anticipos)) {

                foreach ($request->anticipos as $anticipoData) {
                    $anticipoId = $anticipoData['id'] ?? null;
                    $montoAplicado = $anticipoData['monto'] ?? 0;
                    $tipo = $anticipoData['tipo'] ?? null;

                    if (!$anticipoId || $montoAplicado <= 0) {
                        continue;
                    }

                    $anticipo = AnticipoApartado::with('detalles')
                        ->lockForUpdate()
                        ->find($anticipoId);

                    if (!$anticipo) {
                        continue;
                    }

                    // Calcular lo que queda pendiente del anticipo
                    $nuevoDebe  = max(0, $anticipo->debe - $montoAplicado);
                    $nuevoDebia = $anticipo->debia;

                    // Generar folio del abono
                    $anioActual = now()->year;
                    $ultimoFolio = Abono::whereYear('created_at', $anioActual)
                        ->lockForUpdate()
                        ->orderByDesc('id')
                        ->value('folio');

                    $ultimoNumero = 0;
                    if ($ultimoFolio && preg_match('/ABONO-(\d+)-' . $anioActual . '/', $ultimoFolio, $match)) {
                        $ultimoNumero = intval($match[1]);
                    }
                    $nuevoNumero = $ultimoNumero + 1;
                    $folioAbono = sprintf("ABONO-%05d-%d", $nuevoNumero, $anioActual);

                    // Crear abono
                    $abono = Abono::create([
                        'folio'                => $folioAbono,
                        'fecha'                => now(),
                        'abonable_id'          => $anticipo->id,
                        'abonable_type'        => AnticipoApartado::class,
                        'cliente_id'           => $venta->cliente_id,
                        'monto'                => $montoAplicado,
                        'saldo_global_antes'   => $anticipo->debia,
                        'saldo_global_despues' => $nuevoDebia,
                        'referencia'           => 'APLICADO A VENTA',
                        'activo'               => true,
                        'wci'                  => auth()->id(),
                    ]);

                    // Registrar detalle del abono
                    DetalleAbono::create([
                        'abono_id'       => $abono->id,
                        'abonado_a_id'   => $anticipo->id,
                        'abonado_a_type' => AnticipoApartado::class,
                        'monto_antes'    => $anticipo->debia,
                        'abonado'        => $montoAplicado,
                        'saldo_despues'  => $nuevoDebia,
                        'activo'         => true,
                    ]);

                    // Actualizar anticipo o apartado
                    $anticipo->debia  = $nuevoDebia;
                    $anticipo->debe   = $nuevoDebe;
                    $anticipo->abona += $montoAplicado;
                    $anticipo->estatus = 'PASO_A_VENTA';
                    $anticipo->venta_id = $venta->id;
                    $anticipo->save();

                    // Si es un APARTADO, liberar inventario y registrar salida definitiva
                    if ($anticipo->tipo === 'APARTADO') {
                        foreach ($anticipo->detalles as $detalle) {
                            $productoId = $detalle->producto_id;
                            $cantidad   = $detalle->cantidad;

                            if ($productoId && $cantidad > 0) {
                                $inventario = Inventario::where('producto_id', $productoId)
                                    ->lockForUpdate()
                                    ->first();

                                if ($inventario) {
                                    // Restar del apartado
                                    $inventario->producto_apartado = max(0, $inventario->producto_apartado - $cantidad);
                                    $inventario->save();

                                    // Registrar movimiento en Kardex
                                    Kardex::create([
                                        'sucursal_id'     => $inventario->sucursal_id,
                                        'producto_id'     => $productoId,
                                        'movimiento_id'   => $venta->id,
                                        'tipo_movimiento' => 'SALIDA',
                                        'tipo_detalle'    => 'APARTADO',
                                        'fecha'           => now(),
                                        'folio'           => $venta->folio,
                                        'descripcion'     => "Apartado liberado en {$venta->folio}",
                                        'debe'            => 0,
                                        'haber'           => $cantidad,
                                        'saldo'           => $inventario->cantidad,
                                        'wci'             => auth()->id(),
                                        'activo'          => true,
                                    ]);
                                }
                            }
                        }
                    }

                    // Sumar al total aplicado a la venta
                    $venta->monto_recibido += $montoAplicado;
                }

                $venta->save();
            }


            //7. SI ES UN ANTICIPO-APARTADO
            /*
            if (!empty($request->anticipo_apartado_ids)) {
                $idsAnticipos = explode(',', $request->anticipo_apartado_ids);

                $anticipos = AnticipoApartado::with('detalles')
                    ->whereIn('id', $idsAnticipos)
                    ->lockForUpdate()
                    ->get();

                $montoAplicadoTotal = 0;

                foreach ($anticipos as $anticipo) {

                    // 1️⃣ Tomar todo lo que falta del anticipo
                    $montoAplicado = $anticipo->debe;

                    if ($montoAplicado > 0) {

                        // 2️⃣ Calcular lo que queda pendiente del anticipo
                        $nuevoDebe  = 0; // porque se aplicará todo
                        $nuevoDebia = 0;

                        // 3️⃣ Generar folio del abono
                        $anioActual = now()->year;
                        $ultimoFolio = Abono::whereYear('created_at', $anioActual)
                            ->lockForUpdate()
                            ->orderByDesc('id')
                            ->value('folio');

                        $ultimoNumero = 0;
                        if ($ultimoFolio && preg_match('/ABO-(\d+)-' . $anioActual . '/', $ultimoFolio, $match)) {
                            $ultimoNumero = intval($match[1]);
                        }
                        $nuevoNumero = $ultimoNumero + 1;
                        $folioAbono = sprintf("ABO-%05d-%d", $nuevoNumero, $anioActual);

                        // 4️⃣ Crear abono
                        $abono = Abono::create([
                            'folio'               => $folioAbono,
                            'fecha'               => now(),
                            'abonable_id'         => $anticipo->id,
                            'abonable_type'       => AnticipoApartado::class,
                            'cliente_id'          => $venta->cliente_id,
                            'monto'               => $montoAplicado,
                            'saldo_global_antes'  => $anticipo->debia,
                            'saldo_global_despues' => $nuevoDebia,
                            'referencia'          => 'APLICADO A VENTA',
                            'activo'              => true,
                            'wci'                 => auth()->id(),
                        ]);

                        // 5️⃣ Registrar detalle del abono
                        DetalleAbono::create([
                            'abono_id'       => $abono->id,
                            'abonado_a_id'   => $anticipo->id,
                            'abonado_a_type' => AnticipoApartado::class,
                            'monto_antes'    => $anticipo->debia,
                            'abonado'        => $montoAplicado,
                            'saldo_despues'  => $nuevoDebia,
                            'activo'         => true,
                        ]);

                        // 6️⃣ Actualizar anticipo a liquidado
                        $anticipo->debia  = $nuevoDebia;
                        $anticipo->debe   = $nuevoDebe;
                        $anticipo->abona += $montoAplicado;
                        $anticipo->estatus = 'PASO_A_VENTA';
                        $anticipo->venta_id = $venta->id;
                        $anticipo->save();

                        // 🔹 Liberar inventario apartado y registrar salida definitiva
                        foreach ($anticipo->detalles as $detalle) {
                            $productoId = $detalle->producto_id;
                            $cantidad   = $detalle->cantidad;

                            if ($productoId && $cantidad > 0) {
                                $inventario = Inventario::where('producto_id', $productoId)
                                    ->lockForUpdate()
                                    ->first();

                                if ($inventario) {
                                    // 1️⃣ Restar del apartado
                                    $inventario->producto_apartado = max(0, $inventario->producto_apartado - $cantidad);

                                    // 2️⃣ Restar de stock disponible (venta final)
                                    //$inventario->cantidad = max(0, $inventario->cantidad - $cantidad);

                                    $inventario->save();

                                    // 3️⃣ Registrar en Kardex
                                    Kardex::create([
                                        'sucursal_id'     => $inventario->sucursal_id,
                                        'producto_id'     => $productoId,
                                        'movimiento_id'   => $venta->id,
                                        'tipo_movimiento' => 'SALIDA',
                                        'tipo_detalle'    => 'APARTADO',
                                        'fecha'           => now(),
                                        'folio'           => $venta->folio, // folio de la venta
                                        'descripcion'     => "Apartado liberado en {$venta->folio}",
                                        'debe'            => 0,
                                        'haber'           => $cantidad,
                                        'saldo'           => $inventario->cantidad, // - $inventario->producto_apartado,
                                        'wci'             => auth()->id(),
                                        'activo'          => true,
                                    ]);
                                }
                            }
                        }

                        // 7️⃣ Sumar al total aplicado a la venta
                        $montoAplicadoTotal += $montoAplicado;
                        $venta->monto_recibido += $montoAplicado;
                    }
                }

                $venta->save();
            }
            */

            // 8. SI VIENE DE UNA REPARACIÓN
            if ($request->filled('reparacion_id')) {
                $reparacion = Reparacion::with('productos.producto')
                    ->find($request->reparacion_id);

                if ($reparacion) {
                    // 8.1 Regresar productos al inventario de servicio
                    foreach ($reparacion->productos as $detallePrevio) {
                        if ($detallePrevio->producto?->tipo === 'PRODUCTO') {
                            $inventario = Inventario::where('producto_id', $detallePrevio->producto_id)
                                ->where('sucursal_id', auth()->user()->sucursal_id)
                                ->first();

                            if ($inventario) {
                                $inventario->decrement('producto_servicio', $detallePrevio->cantidad);
                            }

                            Kardex::create([
                                'sucursal_id'   => $inventario->sucursal_id ?? null,
                                'producto_id'   => $detallePrevio->producto_id,
                                'movimiento_id' => $reparacion->id,
                                'tipo_movimiento' => 'ENTRADA',
                                'tipo_detalle'  => 'SERVICIO',
                                'fecha'         => now(),
                                'folio'         => $reparacion->folio,
                                'descripcion'   => "Devolución de productos por pasar reparación {$reparacion->folio} a venta {$venta->folio}",
                                'debe'          => $detallePrevio->cantidad,
                                'haber'         => 0,
                                'saldo'         => $inventario->producto_servicio ?? 0,
                                'wci'           => auth()->id(),
                            ]);
                        }
                    }

                    // 8.2 Marcar reparación como entregada y finalizada
                    $reparacion->estatus = 'entregado';
                    $reparacion->finalizada = 1;
                    $reparacion->fecha_entregado = now();
                    $reparacion->venta_id = $venta->id;
                    $reparacion->save();
                }
            }


            DB::commit();

            session()->flash('swal', [
                'icon'  => "success",
                'title' => "Operación correcta",
                'text'  => "La venta se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.ventas.index')->with(['id' => $venta->id]);
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('swal', [
                'icon'  => "error",
                'title' => "Operación fallida.",
                'text'  => "Hubo un error durante el proceso, por favor intente más tarde. " . $e->getMessage(),
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                ],
                'buttonsStyling' => false
            ]);
            return redirect()->back()->withInput($request->all())->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Venta $venta)
    {
        $venta->load([
            'detalles' => function ($query) {
                $query->where('activo', 1)->with('producto.inventarios');
            },
            'pagos',
            'user'
        ]);

        // Pasar a la vista
        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        //
    }

    public function update(Request $request, Venta $venta)
    {
        //
    }

    public function destroy(Venta $venta)
    {
        //
    }

    // ====== CANCELAR TODA LA VENTA ======
    public function cancelarVenta(Request $request, Venta $venta)
    {
        $request->validate([
            'tipo_cancelacion' => 'required|in:devolucion,error',
            'motivo_cancelacion' => 'required|string|max:255',
        ]);

        try {
            $mensajeFlash = null;

            DB::transaction(function () use ($venta, $request, &$mensajeFlash) {
                // GENERAR FOLIO
                $anioActual = now()->year;

                $ultimoFolio = Abono::whereYear('created_at', $anioActual)
                    ->lockForUpdate() // 🔒 bloquea filas de abono de este año mientras corre la transacción
                    ->orderByDesc('id')
                    ->value('folio');

                $ultimoNumero = 0;
                if ($ultimoFolio && preg_match('/ABONO-(\d+)-' . $anioActual . '/', $ultimoFolio, $match)) {
                    $ultimoNumero = intval($match[1]);
                }

                $nuevoNumero = $ultimoNumero + 1;
                $folioAbono = sprintf("ABONO-%05d-%d", $nuevoNumero, $anioActual);

                $tipoCancelacion = $request->tipo_cancelacion;
                $motivo          = $request->motivo_cancelacion;
                $hoy             = now()->toDateString();
                $fechaVenta      = $venta->fecha->toDateString();

                $venta->load(['detalles.producto', 'pagos', 'abonos']);

                $notaExistente = $venta->notaCreditoAsociada();
                $metodosPago   = $venta->pagos->pluck('metodo');

                // 1) Cancelación por ERROR
                if ($tipoCancelacion === 'error') {
                    $this->cancelarYDevolverInventario($venta);

                    if ($notaExistente) {
                        $notaExistente->update([
                            'activo' => 1,
                            'estado' => 'PENDIENTE',
                            'motivo' => 'Reactivada por cancelación por error',
                        ]);
                    }

                    $venta->update(['activo' => 0]);

                    $mensajeFlash = [
                        'icon' => 'info',
                        'title' => 'Cancelación por error',
                        'text'  => $notaExistente
                            ? 'Se reactivó la nota de crédito asociada pero no se generó nueva nota.'
                            : 'Venta cancelada correctamente sin nota de crédito.',
                        'showConfirmButton' => true,
                        'confirmButtonText' => 'Aceptar',
                        'customClass' => [
                            'confirmButton' => 'bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-blue-300',
                            'cancelButton'  => 'bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-300',
                        ],
                        'buttonsStyling' => false,
                    ];
                    return;
                }

                // 2) Venta del día
                if ($fechaVenta === $hoy) {
                    if ($venta->tipo_venta === 'CONTADO') {
                        $soloEfectivo = $metodosPago->count() === 1 && $metodosPago->contains('Efectivo');

                        if ($soloEfectivo) {
                            // Reactivar nota existente sin nueva
                            $this->cancelarYDevolverInventario($venta);

                            if ($notaExistente) {
                                $notaExistente->update([
                                    'activo' => 1,
                                    'estado' => 'PENDIENTE',
                                    'motivo' => 'Reactivada por cancelación de contado (efectivo)[' . $motivo . ']',
                                ]);
                                $mensajeFlash = [
                                    'icon'  => 'info',
                                    'title' => 'Venta cancelada',
                                    'text'  => 'Se reactivó la nota de crédito existente (solo efectivo).',
                                    'showConfirmButton' => true,
                                    'confirmButtonText' => 'Aceptar',
                                    'customClass' => [
                                        'confirmButton' => 'bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-blue-300',
                                        'cancelButton'  => 'bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-300',
                                    ],
                                    'buttonsStyling' => false,
                                ];
                            } else {
                                $mensajeFlash = [
                                    'icon'  => 'info',
                                    'title' => 'Venta cancelada',
                                    'text'  => 'Se canceló la venta de contado en efectivo sin generar nota de crédito.',
                                    'showConfirmButton' => true,
                                    'confirmButtonText' => 'Aceptar',
                                    'customClass' => [
                                        'confirmButton' => 'bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-blue-300',
                                        'cancelButton'  => 'bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-300',
                                    ],
                                    'buttonsStyling' => false,
                                ];
                            }

                            $venta->update(['activo' => 0]);
                            return;
                        }

                        // Pago mixto → reactivar nota y generar nueva si hay excedente
                        $this->cancelarYDevolverInventario($venta);
                        if ($notaExistente) {
                            $notaExistente->update([
                                'activo' => 1,
                                'estado' => 'PENDIENTE',
                                'motivo' => 'Reactivada por cancelación del día con pago mixto [' . $motivo . ']',
                            ]);
                        }

                        // Si los métodos de pago son exactamente 2 (Efectivo + NotaCredito) → NO generar nota nueva
                        if ($metodosPago->count() === 2 && $metodosPago->contains('Efectivo') && $notaExistente) {
                            $venta->update(['activo' => 0]);

                            $mensajeFlash = [
                                'icon'  => 'success',
                                'title' => 'Cancelación realizada',
                                'text'  => 'Se reactivó la nota de crédito existente. No se generó nueva porque el resto fue pagado en efectivo.',
                                'showConfirmButton' => true,
                                'confirmButtonText' => 'Aceptar',
                                'customClass' => [
                                    'confirmButton' => 'bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-blue-300',
                                    'cancelButton'  => 'bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-300',
                                ],
                                'buttonsStyling' => false,
                            ];
                            return;
                        }

                        // En otros casos de pago mixto (ej. Efectivo + Tarjeta, etc.) sí se calcula excedente
                        $montoPagado = $venta->pagos->sum('monto');
                        $excedente   = $montoPagado - ($notaExistente->monto ?? 0);

                        if ($excedente > 0) {
                            $nota = $this->generarNotaCredito($venta, 'Excedente por cancelación [' . $motivo . ']', $excedente);
                            session()->flash('id', $nota->id);
                        }

                        $venta->update(['activo' => 0]);

                        $mensajeFlash = [
                            'icon' => 'success',
                            'title' => 'Cancelación realizada',
                            'text'  => 'Se reactivó la nota existente y se generó una nueva por el excedente.',
                            'showConfirmButton' => true,
                            'confirmButtonText' => 'Aceptar',
                            'customClass' => [
                                'confirmButton' => 'bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-blue-300',
                                'cancelButton'  => 'bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-300',
                            ],
                            'buttonsStyling' => false,
                        ];
                        return;
                    }

                    // Crédito con/sin abonos
                    if ($venta->tipo_venta === 'CRÉDITO') {
                        /*if ($venta->abonos()->exists()) {
                            throw new \Exception('La venta no se puede cancelar porque ya tiene abonos.');
                        }

                        $this->cancelarYDevolverInventario($venta);
                        $venta->update(['activo' => 0]);

                        $mensajeFlash = [
                            'icon' => 'success',
                            'title' => 'Venta cancelada',
                            'text'  => 'Se canceló la venta a crédito sin abonos.',
                            'showConfirmButton' => true,
                            'confirmButtonText' => 'Aceptar',
                            'customClass' => [
                                'confirmButton' => 'bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-blue-300',
                                'cancelButton'  => 'bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-300',
                            ],
                            'buttonsStyling' => false,
                        ];
                        return;*/

                        $totalDevolucion = 0;

                        //Guardar cuánto se ha pagado antes de cancelar
                        $montoPagadoAntes = $venta->abonos()->sum('monto');

                        foreach ($venta->detalles()->where('activo', 1)->get() as $detalle) {
                            $cantidadDevolver = $detalle->cantidad;
                            $this->devolverInventarioProducto($detalle, $cantidadDevolver, $venta);

                            $montoDevolucion = $detalle->precio * $cantidadDevolver;
                            $totalDevolucion += $montoDevolucion;

                            $detalle->update(['cantidad' => 0, 'activo' => 0]);
                        }

                        // 🔹 Recalcular total (debe ser 0)
                        $venta->update([
                            'total'         => 0,
                            'monto_credito' => 0,
                            'activo'        => 0,
                        ]);

                        $this->actualizarCreditoVenta($venta);

                        // 🔹 Registrar abono total (reversión)
                        //$montoTotalDevolucion = $venta->detalles()
                            //->withTrashed()
                        //    ->sum(DB::raw('precio * cantidad'));

                        /*
                        $venta->abonos()->create([
                            'monto'        => $totalDevolucion,
                            'fecha'        => now(),
                            'folio'        => $folioAbono,
                            'tipo'         => 'DEVOLUCION',
                            'descripcion'  => 'Abono por cancelación total de venta a crédito',
                            'wci'          => auth()->id(),
                        ]);
                        */

                        // 🔹 Calcular si hay excedente y crear nota
                        //$montoPagado = $venta->abonos()->sum('monto');
                        //$excedente   = $montoPagado - $venta->total;

                        //if ($excedente > 0) {
                        //    $nota = $this->generarNotaCredito($venta, 'Excedente por cancelación total', $excedente);
                        //    session()->flash('id', $nota->id);
                        //}

                        // 🔹 Generar nota de crédito SOLO por lo que el cliente ya había pagado
                        if ($montoPagadoAntes > 0) {

                            $nota = $this->generarNotaCredito(
                                $venta,
                                'Excedente por cancelación total',
                                $montoPagadoAntes
                            );

                            session()->flash('id', $nota->id);
                        }
                    }
                }

                // 3) Ventas de fechas anteriores
                //$this->cancelarYDevolverInventario($venta);

                $nota = null;
                if ($venta->tipo_venta === 'CONTADO' && $venta->fecha->isBefore(now()->startOfDay())) {
                    $nota = $this->generarNotaCredito($venta, 'Venta de contado anterior');
                    session()->flash('id', value: $nota->id);
                } elseif ($venta->tipo_venta === 'CRÉDITO' && $venta->fecha->isBefore(now()->startOfDay())) {
                    /*if ($venta->abonos()->exists()) {
                        throw new \Exception('La venta no se puede cancelar porque ya tiene abonos.');
                    }
                    $nota = $this->generarNotaCredito($venta, 'Venta a crédito anterior');
                    session()->flash('id', $nota->id);
                    */
                    // 🔹 Cancelación total de venta a crédito
                    $totalDevolucion = 0;
                    foreach ($venta->detalles()->where('activo', 1)->get() as $detalle) {

                        if ($detalle->cantidad <= 0) {
                            continue;
                        }

                        $cantidadDevolver = $detalle->cantidad;
                        $this->devolverInventarioProducto($detalle, $cantidadDevolver, $venta);

                        $montoDevolucion = $detalle->precio * $cantidadDevolver;
                        $totalDevolucion += $montoDevolucion;

                        $detalle->update(['cantidad' => 0, 'activo' => 0]);
                    }

                    // 🔹 Sumar lo que ya había pagado el cliente
                    $montoAbonado = $venta->abonos()->sum('monto');

                    // 🔹 Cancelar venta
                    $venta->update([
                        'total'         => 0,
                        'monto_credito' => 0,
                        'activo'        => 0,
                    ]);

                    $this->actualizarCreditoVenta($venta);

                    // 🔹 Si había abonos, generar nota de crédito equivalente
                    if ($montoAbonado > 0) {
                        $nota = $this->generarNotaCredito(
                            $venta,
                            'Excedente por cancelación total de venta a crédito',
                            $montoAbonado
                        );
                        session()->flash('id', $nota->id);
                    }

                    // 🔹 Registrar movimiento de devolución (solo informativo, sin afectar suma de abonos)
                    /*
                    $venta->abonos()->create([
                        'monto'        => 0, // solo para registro, no suma a pagos
                        'fecha'        => now(),
                        'folio'        => $folioAbono,
                        'tipo'         => 'DEVOLUCION',
                        'descripcion'  => 'Cancelación total de venta a crédito',
                        'wci'          => auth()->id(),
                    ]);
                    */
                }

                $venta->update(['activo' => 0]);

                $mensajeFlash = [
                    'icon' => 'success',
                    'title' => 'Venta cancelada',
                    'text'  => 'La venta fue cancelada y se generó nota de crédito.',
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'Aceptar',
                    'customClass' => [
                        'confirmButton' => 'bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-blue-300',
                        'cancelButton'  => 'bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-300',
                    ],
                    'buttonsStyling' => false,
                ];
            });

            session()->flash('swal', $mensajeFlash);
            return back();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ====== CANCELAR UN PRODUCTO ======
    public function cancelarProducto(Request $request, VentaDetalle $detalle)
    {
        $request->validate([
            'tipo_cancelacion'   => 'required|in:devolucion,error',
            'motivo_cancelacion' => 'required|string|max:255',
            'cantidad'           => 'required|integer|min:1',
        ]);

        try {
            $mensajeFlash = null;

            DB::transaction(function () use ($detalle, $request, &$mensajeFlash) {

                // GENERAR FOLIO
                $anioActual = now()->year;

                $ultimoFolio = Abono::whereYear('created_at', $anioActual)
                    ->lockForUpdate() // 🔒 bloquea filas de abono de este año mientras corre la transacción
                    ->orderByDesc('id')
                    ->value('folio');

                $ultimoNumero = 0;
                if ($ultimoFolio && preg_match('/ABONO-(\d+)-' . $anioActual . '/', $ultimoFolio, $match)) {
                    $ultimoNumero = intval($match[1]);
                }

                $nuevoNumero = $ultimoNumero + 1;
                $folioAbono = sprintf("ABONO-%05d-%d", $nuevoNumero, $anioActual);

                // 🔹 Obtener la venta asociada
                $venta = $detalle->venta;

                if (!$venta) {
                    throw new \Exception('El detalle no tiene venta asociada.');
                }

                // Cargar relaciones necesarias
                $venta->load(['detalles.producto', 'pagos', 'abonos', 'notaCreditos']);


                $tipoCancelacion  = $request->tipo_cancelacion;
                $motivo           = $request->motivo_cancelacion;
                $cantidadDevolver = (int) $request->cantidad;
                $hoy              = now()->toDateString();
                $fechaVenta       = $venta->fecha?->toDateString();

                $notaExistente = $venta->notaCreditoAsociada();
                $metodosPago   = $venta->pagos->pluck('metodo');

                // 🔹 1) Cancelación por ERROR
                if ($tipoCancelacion === 'error') {
                    $this->devolverInventarioProducto($detalle, $cantidadDevolver, $venta, $notaExistente?->id);

                    if ($notaExistente) {
                        $notaExistente->update([
                            'activo' => 1,
                            'estado' => 'PENDIENTE',
                            'motivo' => "Liberada por cancelación por error [{$motivo}]",
                        ]);
                    }

                    $this->ajustarDetalle($detalle, $cantidadDevolver);

                    // Revisar si todos los detalles de la venta están inactivos
                    $this->cancelarVentaSiSinDetallesActivos($venta);

                    $mensajeFlash = [
                        'icon'  => 'info',
                        'title' => 'Cancelación por error',
                        'text'  => 'El producto fue cancelado por error y se devolvió al inventario.',
                        'showConfirmButton' => true,
                        'confirmButtonText' => 'Aceptar',
                        'customClass' => [
                            'confirmButton' => 'bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-blue-300',
                            'cancelButton'  => 'bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-300',
                        ],
                        'buttonsStyling' => false,
                    ];
                    return;
                }

                // 🔹 2) Venta del día
                if ($fechaVenta === $hoy) {
                    if ($venta->tipo_venta === 'CONTADO') {
                        $soloEfectivo = $metodosPago->count() === 1 && $metodosPago->contains('Efectivo');

                        if ($soloEfectivo) {
                            $this->devolverInventarioProducto($detalle, $cantidadDevolver, $venta, $notaExistente?->id);
                            $this->ajustarDetalle($detalle, $cantidadDevolver);

                            // Revisar si todos los detalles de la venta están inactivos
                            $this->cancelarVentaSiSinDetallesActivos($venta);

                            $mensajeFlash = [
                                'icon'  => 'info',
                                'title' => 'Producto cancelado',
                                'text'  => 'Se canceló el producto pagado en efectivo sin generar nota de crédito.',
                                'showConfirmButton' => true,
                                'confirmButtonText' => 'Aceptar',
                                'customClass' => [
                                    'confirmButton' => 'bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-blue-300',
                                    'cancelButton'  => 'bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-300',
                                ],
                                'buttonsStyling' => false,
                            ];
                            return;
                        }

                        // Pago mixto
                        $this->devolverInventarioProducto($detalle, $cantidadDevolver, $venta, $notaExistente?->id);

                        if ($notaExistente) {
                            $notaExistente->update([
                                'activo' => 1,
                                'estado' => 'PENDIENTE',
                                'motivo' => "Reactivada por cancelación de producto del día [{$motivo}]",
                            ]);
                        }

                        $montoDetalle = $detalle->precio * $cantidadDevolver;

                        if ($metodosPago->count() === 2 && $metodosPago->contains('Efectivo') && $notaExistente) {
                            $this->ajustarDetalle($detalle, $cantidadDevolver);
                            // Revisar si todos los detalles de la venta están inactivos
                            $this->cancelarVentaSiSinDetallesActivos($venta);

                            $mensajeFlash = [
                                'icon'  => 'success',
                                'title' => 'Cancelación realizada',
                                'text'  => 'Se reactivó la nota existente. No se generó nueva porque el resto fue en efectivo.',
                                'showConfirmButton' => true,
                                'confirmButtonText' => 'Aceptar',
                                'customClass' => [
                                    'confirmButton' => 'bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-blue-300',
                                    'cancelButton'  => 'bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-300',
                                ],
                                'buttonsStyling' => false,
                            ];
                            return;
                        }

                        $nota = $this->generarNotaCredito($venta, $motivo, $montoDetalle);
                        session()->flash('id', $nota->id);
                        $this->ajustarDetalle($detalle, $cantidadDevolver);
                        // Revisar si todos los detalles de la venta están inactivos
                        $this->cancelarVentaSiSinDetallesActivos($venta);

                        $mensajeFlash = [
                            'icon'  => 'success',
                            'title' => 'Producto cancelado',
                            'text'  => 'Se generó una nota de crédito por el monto del producto cancelado.',
                            'showConfirmButton' => true,
                            'confirmButtonText' => 'Aceptar',
                            'customClass' => [
                                'confirmButton' => 'bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-blue-300',
                                'cancelButton'  => 'bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-300',
                            ],
                            'buttonsStyling' => false,
                        ];
                        return;
                    }

                    if ($venta->tipo_venta === 'CRÉDITO') {
                        /*if ($venta->abonos()->exists()) {
                            throw new \Exception('No se puede cancelar el producto porque la venta ya tiene abonos.');
                        }

                        $this->devolverInventarioProducto($detalle, $cantidadDevolver, $venta, $notaExistente?->id);
                        $detalle->update([
                            'cantidad' => $detalle->cantidad - $cantidadDevolver,
                            'activo'   => $detalle->cantidad - $cantidadDevolver <= 0 ? 0 : 1,
                        ]);

                        $mensajeFlash = [
                            'icon'  => 'success',
                            'title' => 'Producto cancelado',
                            'text'  => 'Se canceló el producto de una venta a crédito sin abonos.',
                            'showConfirmButton' => true,
                            'confirmButtonText' => 'Aceptar',
                            'customClass' => [
                                'confirmButton' => 'bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-blue-300',
                                'cancelButton'  => 'bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-300',
                            ],
                            'buttonsStyling' => false,
                        ];
                        return;*/

                        $this->devolverInventarioProducto($detalle, $cantidadDevolver, $venta, $notaExistente?->id);

                        // 🔹 Calcular monto de devolución
                        $montoDevolucion = $detalle->precio * $cantidadDevolver;

                        // 🔹 Ajustar cantidad del detalle o marcarlo como inactivo
                        //$nuevaCantidad = $detalle->cantidad - $cantidadDevolver;
                        //$detalle->update([
                        //    'cantidad' => max($nuevaCantidad, 0),
                        //    'activo'   => $nuevaCantidad > 0 ? 1 : 0,
                        //]);

                        // 🔹 Registrar abono equivalente a la devolución
                        /*$abonoPorCancelacion = $venta->abonos()->create([
                            'monto'         => $montoDevolucion,
                            'folio'         => $folioAbono,
                            'fecha'         => now(),
                            'referencia'    => 'ABONO POR DEVOLUCION',
                            //'descripcion'  => 'Abono por devolución de producto (' . $detalle->producto->nombre . ')',
                            'cliente_id'    => $venta->cliente_id,
                            'wci'           => auth()->id(),
                        ]);*/

                        //Ajusta el detalle de la venta
                        $this->ajustarDetalle($detalle, $cantidadDevolver);

                        // Calcular monto pagado actualizado
                        $montoPagado = $venta->abonos()->sum('monto');

                        // 🔹 Recalcular totales de la venta
                        $nuevoTotal = $venta->detalles()
                            ->where('activo', 1)
                            ->sum(DB::raw('cantidad * precio'));
                        //dd($nuevoTotal);

                        // Calcular nuevo saldo
                        $saldoPendiente = $nuevoTotal - $montoPagado;

                        $venta->update([
                            'total'         => $nuevoTotal,
                            'monto_credito' => max($saldoPendiente, 0),
                        ]);

                        $this->actualizarCreditoVenta($venta);

                        // 🔹 Si hay excedente (ya pagó más del nuevo total), generar nota de crédito
                        $excedente   = $montoPagado - $nuevoTotal;

                        $montoNotasGeneradas = $venta->notaCreditos()->sum('monto');
                        $excedenteReal = $excedente - $montoNotasGeneradas;

                        if ($excedenteReal > 0) {
                            $nota = $this->generarNotaCredito(
                                $venta,
                                'Excedente por devolución [' . $detalle->producto->nombre . ']',
                                $excedenteReal
                            );
                            session()->flash('id', $nota->id);
                        }

                        // 🔹 Si ya no quedan productos activos, cancelar la venta completamente
                        $detallesActivos = $venta->detalles()->where('activo', 1)->count();

                        if ($detallesActivos === 0) {
                            $venta->update([
                                'total'         => 0,
                                'monto_credito' => 0,
                                'activo'        => 0,
                            ]);

                            $this->actualizarCreditoVenta($venta);

                            $montoAbonado = $venta->abonos()->sum('monto');

                            $montoNotasGeneradas = $venta->notaCreditos()->sum('monto');
                            $montoRestante = $montoAbonado - $montoNotasGeneradas;
                            if ($montoRestante  > 0) {
                                $nota = $this->generarNotaCredito(
                                    $venta,
                                    'Excedente por cancelación total tras devolución',
                                    $montoRestante
                                );
                                session()->flash('id', $nota->id);
                            }
                        }
                    }
                }

                // 🔹 3) Ventas de fechas anteriores
                //$this->devolverInventarioProducto($detalle, $cantidadDevolver, $venta, $notaExistente?->id);

                $nota = null;
                if ($venta->tipo_venta === 'CONTADO' && $venta->fecha->isBefore(now()->startOfDay())) {
                    $nota = $this->generarNotaCredito($venta, $motivo, $detalle->precio * $cantidadDevolver);
                    session()->flash('id', $nota->id);
                } elseif ($venta->tipo_venta === 'CRÉDITO' && $venta->fecha->isBefore(now()->startOfDay())) {
                    //if ($venta->abonos()->exists()) {
                    //    throw new \Exception('No se puede cancelar el producto porque la venta ya tiene abonos.');
                    //}
                    $this->devolverInventarioProducto($detalle, $cantidadDevolver, $venta, $notaExistente?->id);

                    // 🔹 Calcular monto de devolución
                    $montoDevolucion = $detalle->precio * $cantidadDevolver;

                    // 🔹 Ajustar cantidad del detalle o marcarlo como inactivo
                    //$nuevaCantidad = $detalle->cantidad - $cantidadDevolver;
                    //$detalle->update([
                    //    'cantidad' => max($nuevaCantidad, 0),
                    //    'activo'   => $nuevaCantidad > 0 ? 1 : 0,
                    //]);





                      // 🔹 Registrar abono equivalente a la devolución
                        /*$abonoPorCancelacion = $venta->abonos()->create([
                            'monto'        => $montoDevolucion,
                            'fecha'        => now(),
                            'folio'        => $folioAbono,
                            'referencia'    => 'ABONO POR DEVOLUCION',
                            //'descripcion'  => 'Abono por devolución de producto (' . $detalle->producto->nombre . ')',
                            'cliente_id'    => $venta->cliente_id,
                            'wci'          => auth()->id(),
                        ]);*/

                        //Ajusta el detalle de la venta
                        $this->ajustarDetalle($detalle, $cantidadDevolver);

                        // Calcular monto pagado actualizado
                        $montoPagado = $venta->abonos()->sum('monto');

                        // 🔹 Recalcular totales de la venta
                        $nuevoTotal = $venta->detalles()
                            ->where('activo', 1)
                            ->sum(DB::raw('cantidad * precio'));

                        // Calcular nuevo saldo
                        $saldoPendiente = $nuevoTotal - $montoPagado;

                        $venta->update([
                            'total'         => $nuevoTotal,
                            'monto_credito' => max($saldoPendiente, 0),
                        ]);

                        $this->actualizarCreditoVenta($venta);




                    /*
                    // 🔹 Recalcular totales de la venta
                    $nuevoTotal = $venta->detalles()
                        ->where('activo', 1)
                        ->sum(DB::raw('cantidad * precio'));

                    $venta->update([
                        'total'         => $nuevoTotal,
                        'monto_credito' => $nuevoTotal - $venta->abonos()->sum('monto'),
                    ]);

                    //Ajusta el detalle de la venta
                    $this->ajustarDetalle($detalle, $cantidadDevolver);

                    // 🔹 Registrar abono equivalente a la devolución
                    $abonoPorCancelacion = $venta->abonos()->create([
                        'monto'        => $montoDevolucion,
                        'fecha'        => now(),
                        'folio'        => $folioAbono,
                        'tipo'         => 'DEVOLUCION',
                        'descripcion'  => 'Abono por devolución de producto (' . $detalle->producto->nombre . ')',
                        'wci'          => auth()->id(),
                    ]);

                    // insertamos los valos faltantes de abonos
                    $abonoPorCancelacion->update([
                        'saldo_global_antes'   => $nuevoTotal,
                        'saldo_global_despues' => max($saldoPendiente, 0),
                    ]);
                    */

                    // 🔹 Si hay excedente (ya pagó más del nuevo total), generar nota de crédito
                    $montoPagado = $venta->abonos()->sum('monto');
                    $excedente   = $montoPagado - $nuevoTotal;

                    if ($excedente > 0) {
                        $nota = $this->generarNotaCredito($venta, 'Excedente por devolución [' . $detalle->producto->nombre . ']', $excedente);
                        session()->flash('id', $nota->id);
                    }
                }

                //$this->ajustarDetalle($detalle, $cantidadDevolver);
                // Revisar si todos los detalles de la venta están inactivos
                $this->cancelarVentaSiSinDetallesActivos($venta);

                $mensajeFlash = [
                    'icon'  => 'success',
                    'title' => 'Producto cancelado',
                    'text'  => $nota
                        ? 'El producto fue cancelado y se generó nota de crédito.'
                        : 'El producto fue cancelado correctamente.',
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'Aceptar',
                    'customClass' => [
                        'confirmButton' => 'bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-blue-300',
                        'cancelButton'  => 'bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring-4 focus:ring-gray-300',
                    ],
                    'buttonsStyling' => false,
                ];
            });

            session()->flash('swal', $mensajeFlash);
            return back();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Funciones auxiliares
    private function generarNotaCredito(Venta $venta, $motivo, $monto = null, $estado = 'PENDIENTE')
    {

        //return $venta->notaCreditos()->create([
        $nota = $venta->notaCreditos()->create([
            'cliente_id' => $venta->cliente_id,
            'monto'      => $monto ?? $venta->total,
            'motivo'     => $motivo,
            'tipo'       => 'CANCELACION', // o 'devolucion', 'garantia'
            'estado'     => $estado,
            'activo'     => 1,
        ]);

        return $nota;
    }

    private function cancelarYDevolverInventario(Venta $venta)
    {
        // Usamos sucursal fija desde la venta
        $sucursalId = $venta->sucursal_id ?? $venta->user->sucursal_id ?? auth()->user()->sucursal_id;

        foreach ($venta->detalles as $detalle) {
            if ($detalle->producto_id && $detalle->producto) {

                // Buscar inventario en la sucursal
                $inventario = Inventario::where('producto_id', $detalle->producto_id)
                    ->where('sucursal_id', $sucursalId)
                    ->first();

                if ($inventario) {

                    // Devolver al inventario
                    $inventario->cantidad += $detalle->cantidad;
                    $inventario->updated_at = now();
                    $inventario->save();

                    // Último saldo en kardex
                    $ultimoRegistro = Kardex::where('producto_id', $detalle->producto_id)
                        ->where('sucursal_id', $sucursalId)
                        ->orderBy('id', 'desc')
                        ->first();

                    $saldoActual = $ultimoRegistro ? $ultimoRegistro->saldo : 0;
                    $nuevoSaldo  = $saldoActual + $detalle->cantidad;
                    // Registrar movimiento en kardex
                    Kardex::create([
                        'sucursal_id'   => $sucursalId,
                        'producto_id'   => $detalle->producto_id,
                        'movimiento_id' => $venta->id,
                        'tipo_movimiento' => 'ENTRADA',
                        'tipo_detalle'    => 'CANCELACION',
                        'fecha'         => now(),
                        'folio'         => $venta->folio,
                        'debe'          => $detalle->cantidad,
                        'haber'         => 0,
                        'saldo'         => $nuevoSaldo,
                        'wci'           => auth()->id(),
                        'activo'        => 1, // <- importante
                    ]);
                }
            }

            // Desactivar detalle de la venta
            $detalle->activo = 0;
            $detalle->save();
        }

        // Desactivar la venta
        $venta->activo = 0;
        $venta->save();
    }

    protected function devolverInventarioProducto(VentaDetalle $detalle, int $cantidad, $venta, ?int $notaId = null)
    {
        $producto = $detalle->producto;
        if (!$producto) return;

        // 🔹 Determinar sucursal
        $sucursalId = $venta->sucursal_id ?? $venta->user->sucursal_id ?? auth()->user()->sucursal_id;

        // 🔹 Actualizar inventario
        $inventario = Inventario::where('producto_id', $detalle->producto_id)
            ->where('sucursal_id', $sucursalId)
            ->first();

        if ($inventario) {
            $inventario->cantidad += $cantidad;
            $inventario->updated_at = now();
            $inventario->save();

            // 🔹 Registrar movimiento en kardex
            $ultimoRegistro = Kardex::where('producto_id', $detalle->producto_id)
                ->where('sucursal_id', $sucursalId)
                ->orderBy('id', 'desc')
                ->first();

            $saldoActual = $ultimoRegistro ? $ultimoRegistro->saldo : 0;
            $nuevoSaldo  = $saldoActual + $cantidad;

            Kardex::create([
                'sucursal_id'     => $sucursalId,
                'producto_id'     => $detalle->producto_id,
                'movimiento_id'   => $venta->id,
                'tipo_movimiento' => 'ENTRADA',
                'tipo_detalle'    => 'CANCELACION',
                'fecha'           => now(),
                'folio'           => $venta->folio,
                'debe'            => $cantidad,
                'haber'           => 0,
                'saldo'           => $nuevoSaldo,
                'wci'             => auth()->id(),
                'activo'          => 1,
            ]);
        }

        // 🔹 Registrar devolución en la tabla venta_devoluciones
        $detalle->devoluciones()->create([
            'venta_id'         => $venta->id,
            'venta_detalle_id' => $detalle->id,
            'nota_credito_id'  => $notaId,
            'cantidad'         => $cantidad,
            'monto'            => $detalle->precio_unitario * $cantidad,
            'motivo'           => 'Producto devuelto',
        ]);
    }

    protected function ajustarDetalle(VentaDetalle $detalle, int $cantidadDevolver)
    {
        //dd($detalle->cantidad , $cantidadDevolver);
        $detalle->update([
            'cantidad' => $detalle->cantidad - $cantidadDevolver,
            'activo'   => $detalle->cantidad - $cantidadDevolver <= 0 ? 0 : 1,
        ]);
    }

    protected function cancelarVentaSiSinDetallesActivos(Venta $venta)
    {
        if ($venta->detalles()->where('activo', 1)->count() === 0) {
            $venta->update(['activo' => 0]);
        }
    }

    protected function actualizarCreditoVenta(Venta $venta)
    {
        $credito = $venta->credito;

        if (!$credito) {
            return;
        }

        $totalVenta   = $venta->total;
        $montoAbonado = $venta->abonos()->sum('monto');

        // saldo real
        $saldoActual = max($totalVenta - $montoAbonado, 0);

        $credito->update([
            'monto_credito' => $saldoActual,
            'saldo_actual'  => $saldoActual,

            // 1 = liquidado
            'liquidado' => $saldoActual == 0 ? 1 : 0,

            // solo se desactiva si la venta se cancela
            'activo' => $venta->activo ? 1 : 0,
        ]);
    }

    public function ticket($id)
    {
        $venta = Venta::with([
            'cliente',
            'detalles.producto',
            'detalles.devoluciones.notaCredito',
            'notaCreditos.ventasAplicadas',
            'pagos', // incluir pagos
            'pagos.pagable', // para identificar si fue nota crédito o anticipo
        ])->findOrFail($id);


        $montoNotasCredito = $venta->notaCreditos->sum('monto');
        $totalNeto = $venta->total - $montoNotasCredito;

        $totalPagadoAjustado = $venta->monto_recibido - $montoNotasCredito;

        $user = auth()->user();
        $userPrinterSize = 80;

        $size = match ($userPrinterSize) {
            58 => [0, 0, 140, 1440],
            80 => [0, 0, 212, 1440],
            default => [0, 0, 0, 0],
        };

        $pdf = PDF::loadView('comprobantes.ticket_venta', compact(
            'venta',
            'userPrinterSize',
            'montoNotasCredito',
            'totalNeto',
            'totalPagadoAjustado'
        ))->setPaper($size, 'portrait');

        //dd( $venta);

        return $pdf->stream();
    }

    public function ticket_uno($id)
    {
        $venta = Venta::with([
            'cliente',
            'detalles.producto',
            'detalles.devoluciones.notaCredito', // devoluciones con su nota
            'notaCreditos'
        ])->findOrFail($id);

        // Sumar todas las notas de crédito activas de la venta
        $montoNotasCredito = $venta->notaCreditos->sum('monto');

        // Total neto después de devoluciones
        $totalNeto = $venta->total - $montoNotasCredito;

        $totalPagadoAjustado = $venta->monto_recibido - $venta->notaCreditos->sum('monto');
        //  - CREAMOS EL PDF DE LA VENTA ----
        $user = auth()->user();
        $userPrinterSize = 80;

        $size = match ($userPrinterSize) {
            58 => [0, 0, 140, 1440],
            80 => [0, 0, 212, 1440],
            default => [0, 0, 0, 0],
        };

        $pdf = PDF::loadView('comprobantes.ticket_venta', compact('venta', 'userPrinterSize', 'montoNotasCredito', 'totalNeto', 'totalPagadoAjustado'))
            ->setPaper($size, 'portrait');
        return $pdf->stream();
    }

    public function buscarVenta(Request $request)
    {
        $folio = $request->folio;
        $venta = Venta::where('folio', $folio)->first();

        if ($venta) {
            return response()->json([
                'venta' => [
                    'id' => $venta->id,
                    'cliente' => $venta->cliente->full_name,
                    'total' => $venta->total,
                ]
            ]);
        } else {
            return response()->json(['venta' => null]);
        }
    }

    public function ventas_index_ajax(Request $request)
    {
        if ($request->origen == 'venta.index') {

            $hoy = Carbon::today();
            $hace7dias = $hoy->copy()->subDays(7);

            // --- VENTAS --- //
            //$data = Venta::with('cliente')
            //    ->whereBetween('fecha', [
            //        $hace7dias->startOfDay(),
            //        $hoy->copy()->endOfDay()
            //    ])
            //    ->orderBy('fecha', 'asc')
            //    ->get();


            $ventas = Venta::with('cliente')
                ->whereBetween('fecha', [
                    $hace7dias->startOfDay(),
                    $hoy->copy()->endOfDay()
                ])
                ->orderBy('fecha', 'desc')
                ->get()->map(function ($item) {


                return [
                    'id'        => $item->id,
                    'fecha'    => Carbon::parse($item->fecha)->format('d/m/Y H:i:s'),
                    'folio' => $item->folio,
                    'cliente'  => $item->cliente ? $item->cliente->full_name : 'SIN CLIENTE',
                    'tipo_venta'  => $item->tipo_venta,
                    'total'  => '$' . number_format($item->total, 2, '.', ','),
                    'acciones' => e(view('ventas.partials.acciones', compact('item'))->render()),
                ];
            });

            return response()->json([ 'data' => $ventas ]);
        }
    }
}
