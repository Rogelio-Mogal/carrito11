<?php

namespace App\Http\Controllers;

use App\Models\CajaMovimiento;
use App\Models\CajaTurno;
use App\Models\NotaCredito;
use App\Models\TipoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaMovimientoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:caja_movimeinto.ver')
        ->only(['index', 'show']);

        $this->middleware('permission:caja_movimeinto.crear')
            ->only(['create', 'store']);

        $this->middleware('permission:caja_movimeinto.editar')
            ->only(['edit', 'update']);

        $this->middleware('permission:caja_movimeinto.eliminar')
            ->only(['destroy']);
    }

    public function index()
    {
        return view('caja.index');
    }

    public function create()
    {
        $caja = new CajaMovimiento();
        $metodo = 'create';
        return view('caja.create', compact('metodo','caja'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:entrada,salida',
            'monto' => 'required|decimal:0,2|min:1',
            'motivo' => 'required|string|min:2|max:1500',
        ]);
        try{

            DB::transaction(function () use ($request) {
                // Verifico y obtengo el turno actual
                $cajaTurno = CajaTurno::turnoAbierto(auth()->id());

                if (!$cajaTurno) {
                    //return back()->with('error', 'No tienes un turno de caja abierto');
                    session()->flash('swal', [
                        'icon' => "error",
                        'title' => "Operación fallida",
                        'text' => "No tienes un turno de caja abierto",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                        ],
                        'buttonsStyling' => false
                    ]);

                    return redirect()->back()
                        ->withInput($request->all()) // Aquí solo pasas los valores del formulario
                        ->with('status', 'No tienes un turno de caja abierto.')
                        ->withErrors(['error' => 'No tienes un turno de caja abierto.']); // Aquí pasas el mensaje de error

                }

                // Crear el movimiento de caja
                $caja = CajaMovimiento::create([
                    'caja_turno_id' => $cajaTurno->id,
                    'monto'         => $request->monto,
                    'tipo'          => $request->tipo,
                    'motivo'        => $request->motivo,
                    'fecha'         => now(),
                    'user_id'       => auth()->id(),
                ]);

                // Apuntarlo a sí mismo como origen
                $caja->origen()->associate($caja);
                $caja->save();
            });

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El moviento en caja se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.caja.movimiento.index');
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => "error",
                'title' => "Operación fallida",
                'text' => "Hubo un error durante el proceso, por favor intente más tarde.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->back()
                ->withInput($request->all()) // Aquí solo pasas los valores del formulario
                ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
        }
    }

    public function show(CajaMovimiento $cajaMovimiento)
    {
        //
    }

    public function edit(CajaMovimiento $cajaMovimiento)
    {
        //
    }

    // NO SE VA A UTILIZAR EL MÉTODO UPDATE, SE CAMBIO POR EL METODO DEVOLVER
    public function update(Request $request, $id)
    {
        $nota = NotaCredito::findOrFail($id);

        if (!$nota->activo) {
            return response()->json(['message' => 'La nota de crédito no está activa'], 422);
        }

        // Calcular saldo en caja
        $saldo = $this->getSaldoEfectivoDia();

        if ($request->monto > $saldo) {
            return response()->json(['message' => 'No hay suficiente efectivo en caja'], 422);
        }

        DB::transaction(function () use ($request, $nota) {
            // Verifico y obtengo el turno actual
            $cajaTurno = CajaTurno::turnoAbierto(auth()->id());

            if (!$cajaTurno) {
                session()->flash('swal', [
                    'icon' => "error",
                    'title' => "Operación fallida",
                    'text' => "No tienes un turno de caja abierto",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false
                ]);

                return redirect()->back()
                    ->withInput($request->all()) // Aquí solo pasas los valores del formulario
                    ->with('status', 'No tienes un turno de caja abierto.')
                    ->withErrors(['error' => 'No tienes un turno de caja abierto.']); // Aquí pasas el mensaje de error
            }

            // Crear movimiento en caja
            CajaMovimiento::create([
                'caja_turno_id' => $cajaTurno->id,
                'monto'         => $request->monto,
                'tipo'          => $request->tipo, // 'devolucion'
                'motivo'        => $request->motivo,
                'fecha'         => now(),
                'origen_id'     => $nota->id,
                'origen_type'   => $request->origen_type,
                'user_id'       => auth()->id(),
                'activo'        => true
            ]);

            // Marcar la nota como inactiva si quieres
            $nota->activo = false;
            $nota->estado = 'DEVUELTO';
            $nota->save();
        });

        return response()->json(['message' => 'Devolución registrada correctamente']);
    }

    public function devolver(Request $request)
    {
        $notas = NotaCredito::whereIn('id', $request->nota_ids)
            ->where('activo', 1)
            ->get();

        if ($notas->isEmpty()) {
            return response()->json(['message' => 'No hay notas válidas'], 422);
        }

        $montoTotal = $notas->sum('monto');

        // Validar contra lo que viene del frontend (seguridad)
        if ((float)$request->monto !== (float)$montoTotal) {
            return response()->json(['message' => 'El monto no coincide con las notas'], 422);
        }

        $saldo = $this->getSaldoEfectivoTurno();

        if ($montoTotal > $saldo) {
            return response()->json(['message' => 'No hay suficiente efectivo en caja'], 422);
        }

        DB::transaction(function () use ($notas, $montoTotal, $request) {
            // Verifico y obtengo el turno actual
            $cajaTurno = CajaTurno::turnoAbierto(auth()->id());

            if (!$cajaTurno) {
                session()->flash('swal', [
                    'icon' => "error",
                    'title' => "Operación fallida",
                    'text' => "No tienes un turno de caja abierto",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false
                ]);

                return redirect()->back()
                    ->withInput($request->all()) // Aquí solo pasas los valores del formulario
                    ->with('status', 'No tienes un turno de caja abierto.')
                    ->withErrors(['error' => 'No tienes un turno de caja abierto.']); // Aquí pasas el mensaje de error
            }

            $notaBase = $notas->first();

            // 🔹 Crear un SOLO movimiento en caja
            CajaMovimiento::create([
                'caja_turno_id' => $cajaTurno->id,
                'monto'         => $montoTotal,
                'tipo'          => $request->tipo, // 'devolucion'
                'motivo'        => $request->motivo,
                'fecha'         => now(),
                'origen_id'     => $notaBase->id,
                'origen_type'   => get_class($notaBase), // ← dinámico (mejor)
                'user_id'       => auth()->id(),
                'activo'        => true
            ]);

            // 🔹 Marcar TODAS las notas como devueltas
            foreach ($notas as $nota) {
                $nota->update([
                    'activo' => 0,
                    'estado' => 'DEVUELTO'
                ]);
            }
        });

        return response()->json(['message' => 'Devolución registrada correctamente']);
    }

    public function getSaldoEfectivoTurno()
    {
        $turno = CajaTurno::where('user_id', auth()->id())
            ->whereNull('fecha_cierre')
            ->first();

        if (!$turno) {
            return 0;
        }

        $cajaTurnoId = $turno->id;

        $efectivoInicial = $turno->efectivo_inicial ?? 0;

        // 💳 Ventas en efectivo (CORRECTO ahora)
        $ventasEfectivo = TipoPago::where('metodo', 'Efectivo')
            ->where('activo', true)
            ->where('caja_turno_id', $cajaTurnoId)
            ->sum('monto');

        // ➕ Entradas manuales
        $entradas = CajaMovimiento::where('tipo', 'entrada')
            ->where('activo', true)
            ->where('caja_turno_id', $cajaTurnoId)
            ->sum('monto');

        // ➖ Salidas
        $salidas = CajaMovimiento::where('tipo', 'salida')
            ->where('activo', true)
            ->where('caja_turno_id', $cajaTurnoId)
            ->sum('monto');

        return $efectivoInicial + $ventasEfectivo + $entradas - $salidas;
    }

    public function getSaldoEfectivoDia_()
    {
        $hoy = now()->toDateString();

        //Efectivo apertura caja
        $turno = CajaTurno::where('estado', 'abierto')
            ->where('user_id', auth()->id())
            ->first();

        if (!$turno) {
            return 0;
        }

        $efectivoInicial = $turno?->efectivo_inicial ?? 0;

        // Total efectivo de ventas del día
        $ventasEfectivo = TipoPago::where('metodo', 'Efectivo')
            ->where('activo', true)
            ->where('wci', auth()->user()->id)
            ->whereDate('created_at', $hoy)
            ->sum('monto');

        // Entradas del día
        $entradas = CajaMovimiento::where('tipo', 'entrada')
            ->where('activo', true)
            ->where('user_id', auth()->user()->id)
            ->whereDate('fecha', $hoy)
            ->sum('monto');

        // Salidas del día
        $salidas = CajaMovimiento::where('tipo', 'salida')
            ->where('activo', true)
            ->where('user_id', auth()->user()->id)
            ->whereDate('fecha', $hoy)
            ->sum('monto');

        return $ventasEfectivo + $entradas - $salidas;
    }

    public function destroy(CajaMovimiento $cajaMovimiento)
    {
        //
    }

    public function caja_index_ajax(Request $request)
    {
        // REGISTROS DE CAJA PARA EL INDEX
        if ($request->origen == 'caja.index') {

            $caja = CajaMovimiento::orderBy('fecha', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'monto' => $item->monto,
                    'tipo' => ucfirst($item->tipo), // Entrada o Salida
                    'motivo' => $item->motivo ?? 'N/A',
                    'fecha' => $item->fecha->format('Y-m-d H:i:s'),
                    'activo' => $item->activo ? 'ACTIVO' : 'CANCELADO',
                    'origen' => $item->origen ? class_basename($item->origen_type) . ' #' . $item->origen_id : 'N/A',
                ];
            });


            return response()->json(['data' => $caja]);
        }
    }
}
