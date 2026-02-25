<?php

namespace App\Http\Controllers;

use App\Models\AnticipoApartado;
use App\Models\CajaMovimiento;
use App\Models\CajaTurno;
use App\Models\TipoPago;
use App\Models\Venta;
use App\Models\VentaCredito;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CajaTurnoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:turnos.ver')
        ->only(['index', 'show']);

        $this->middleware('permission:turnos.crear')
            ->only(['create', 'store']);
    }

    public function index(Request $request)
    {
        $mes = $request->input('mes');
        $query = CajaTurno::with('usuario')->orderBy('id','desc');

        if ($mes) {
            $anio = substr($mes, 0, 4);
            $mesNum = substr($mes, 5, 2);
            $query->whereYear('fecha_apertura', $anio)
                ->whereMonth('fecha_apertura', $mesNum);
        }

        if ($request->ajax()) {
            $turnos = $query->get();
            return response()->json($turnos->map(function($item) {
                return [
                    'id' => $item->id,
                    'usuario' => $item->usuario->name ?? 'Desconocido',
                    'turno' => $item->turno,
                    'efectivo_inicial' => '$' . number_format($item->efectivo_inicial, 2),
                    'efectivo_calculado' => '$' . number_format($item->efectivo_calculado, 2),
                    'efectivo_real' => '$' . number_format($item->efectivo_real, 2),
                    'diferencia' => '$' . number_format($item->diferencia, 2),
                    'fecha_apertura' => $item->fecha_apertura->format('d/m/Y H:i:s'),
                    'fecha_cierre' => $item->fecha_cierre?->format('d/m/Y H:i:s') ?? '-',
                    'estado' => $item->estado,
                ];
            }));
        }

        $turnos = $query->get();
        $now = Carbon::now();
        return view('caja_turno.index', compact('mes','now','turnos'));
    }

    public function index_usus(Request $request)
    {
        setlocale(LC_ALL, "Spanish");
        $mes = $request->input('mes'); // obtener mes del formulario
        $query = CajaTurno::with('usuario')->orderBy('id', 'DESC');
        if ($mes) {
            // Esperamos formato 'YYYY-MM'
            $anio = substr($mes, 0, 4);
            $mesNum = substr($mes, 5, 2);

            $query->whereYear('fecha_apertura', $anio)
                ->whereMonth('fecha_apertura', $mesNum);
        }
        $turnos = $query->get();
        $now = Carbon::now();
        //foreach ($turno as $compra) {
        //    $compra->usuario_nombre = User::find($compra->wci)->name;
        //}


        return view('caja_turno.index', compact('mes','now','turnos'));
    }

    public function create_opcw(Request $request)
    {
        $turnoAbierto = CajaTurno::where('estado', 'abierto')
            ->where('usuario_id', auth()->id())
            ->first();

        if (!$turnoAbierto) {
            return redirect()->route('admin.caja.turno.create')
                ->with('warning', 'Debes abrir caja antes de registrar ventas.');
        }

        // Detectar si el turno abierto es de días anteriores
        $fechaApertura = $turnoAbierto->fecha_apertura->toDateString();
        $hoy = now()->toDateString();

        if ($fechaApertura < $hoy) {
            // Calcular efectivo acumulado hasta hoy para mostrar al usuario
            $fechaInicio = $turnoAbierto->fecha_apertura;
            $fechaFin = now();

            $ventasEfectivo = TipoPago::where('metodo', 'Efectivo')
                ->where('activo', 1)
                ->whereHasMorph(
                    'pagable',
                    [Venta::class],
                    fn($q) => $q->where('wci', auth()->id())
                                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                )
                ->sum('monto');

            $abonosVentas = TipoPago::where('metodo', 'Efectivo')
                ->where('activo', 1)
                ->whereHasMorph(
                    'pagable',
                    [VentaCredito::class],
                    fn($q) => $q->where('wci', auth()->id())
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                )
                ->sum('monto');

            $abonosAnticipos = TipoPago::where('metodo', 'Efectivo')
                ->where('activo', 1)
                ->whereHasMorph(
                    'pagable',
                    [AnticipoApartado::class],
                    fn($q) => $q->where('wci', auth()->id())
                                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                )
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

            $totalEfectivo = $turnoAbierto->efectivo_inicial
                            + $ventasEfectivo
                            + $abonosVentas
                            + $abonosAnticipos
                            + $entradas
                            - $salidas;

            if ($turnoAbierto) {
                $metodo = 'cierre';
                $caja = $turnoAbierto;
            } else {
                $metodo = 'apertura';
                $caja = new CajaTurno();
            }

            // Redirigir a la vista de cierre de turno para que el usuario ingrese efectivo real
            return view('caja_turno.create', [
                'turno' => $turnoAbierto,
                'totalEfectivoCalculado' => $totalEfectivo,
                'ventasEfectivo' => $ventasEfectivo,
                'abonosVentas' => $abonosVentas,
                'abonosAnticipos' => $abonosAnticipos,
                'entradas' => $entradas,
                'salidas' => $salidas,
                'metodo' => $metodo
            ]);
        }

        // Turno del día actual: mostrar ventas normalmente
        $totalEfectivo = $turnoAbierto->efectivo_inicial;

        return view('ventas.create', compact('turnoAbierto', 'totalEfectivo'));
    }

    public function create()
    {
        $turnoAbierto = CajaTurno::where('estado', 'abierto')
        ->where('usuario_id', auth()->id())
        ->first();

        // Determinar fecha de inicio para calcular efectivo
        if ($turnoAbierto) {
            // Si hay un turno abierto, usamos la fecha de apertura del turno
            $fechaInicio = $turnoAbierto->fecha_apertura;
        } else {
            // Si no hay turno abierto, usamos la fecha de hoy
            $fechaInicio = now()->startOfDay();
        }
        $fechaFin = now();

        // 1️⃣ Ventas en efectivo
        $ventasEfectivo = TipoPago::where('metodo', 'Efectivo')
            ->where('activo', 1)
            ->whereHasMorph(
                'pagable',
                [Venta::class],
                fn($q) => $q->where('user_id', auth()->id())
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
        $totalEfectivo = ($turnoAbierto?->efectivo_inicial ?? 0) + $efectivoOperaciones + $entradas - $salidas;

        if ($turnoAbierto) {
            $metodo = 'cierre';
            $caja = $turnoAbierto;
        } else {
            $metodo = 'apertura';
            $caja = new CajaTurno();
        }

        return view('caja_turno.create', compact('metodo', 'caja','totalEfectivo'));
    }

    public function store(Request $request)
    {
        // Verificar si hay turno abierto para este usuario
        $turnoAbierto = CajaTurno::where('estado', 'abierto')
            ->where('usuario_id', auth()->id())
            ->first();

        if ($turnoAbierto) {
            // ===== CIERRE DE TURNO =====
            $request->validate([
                'efectivo_real' => 'required|numeric|min:0',
            ]);

            $fechaInicio = now()->startOfDay(); //$turnoAbierto->fecha_apertura;
            $fechaFin = now();

            // 1️⃣ Ventas en efectivo
            $ventasEfectivo = TipoPago::where('metodo', 'Efectivo')
                ->where('activo', 1)
                ->whereHasMorph(
                    'pagable',
                    [Venta::class],
                    fn($q) => $q->where('user_id', auth()->id())
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

            // Total de efectivo de operaciones
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

            // 5️⃣ Guardar cierre de turno
            $turnoAbierto->efectivo_calculado = $turnoAbierto->efectivo_inicial
                                                + $efectivoOperaciones
                                                + $entradas
                                                - $salidas;

            $turnoAbierto->efectivo_real = $request->efectivo_real;
            $turnoAbierto->diferencia = $turnoAbierto->efectivo_real - $turnoAbierto->efectivo_calculado;
            $turnoAbierto->fecha_cierre = now();
            $turnoAbierto->estado = 'cerrado';

            // Guardar detalle de cálculo para auditoría
            $turnoAbierto->detalle_calculo = [
                'ventas_efectivo' => $ventasEfectivo,
                'abonos_ventas' => $abonosVentas,
                'abonos_anticipos' => $abonosAnticipos,
                'entradas' => $entradas,
                'salidas' => $salidas
            ];

            $turnoAbierto->save();

            return redirect()->route('admin.ventas.index')->with('success', 'Turno cerrado correctamente.');

        } else {
            // ===== APERTURA DE TURNO =====
            $request->validate([
                'efectivo_inicial' => 'required|numeric|min:0',
            ]);

            // Número de turno del día
            $turno = CajaTurno::whereDate('fecha_apertura', now()->toDateString())->count() + 1;

            CajaTurno::create([
                'usuario_id' => auth()->id(),
                'turno' => $turno,
                'efectivo_inicial' => $request->efectivo_inicial,
                'efectivo_calculado' => $request->efectivo_inicial,
                'estado' => 'abierto',
                'fecha_apertura' => now(),
            ]);

            return redirect()->route('admin.ventas.create')->with('success', "Caja abierta (Turno {$turno}).");
        }
    }

    public function show(CajaTurno $cajaTurno)
    {
        //
    }

    public function edit(CajaTurno $cajaTurno)
    {
        //
    }

    public function update(Request $request, CajaTurno $cajaTurno)
    {
        //
    }

    public function destroy(CajaTurno $cajaTurno)
    {
        //
    }
}
