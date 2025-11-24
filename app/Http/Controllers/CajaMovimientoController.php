<?php

namespace App\Http\Controllers;

use App\Models\CajaMovimiento;
use App\Models\NotaCredito;
use App\Models\TipoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaMovimientoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
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
                // Crear el movimiento de caja
                $caja = CajaMovimiento::create([
                    'monto'      => $request->monto,
                    'tipo'       => $request->tipo,
                    'motivo'     => $request->motivo,
                    'fecha'      => now(),
                    'usuario_id' => auth()->id(),
                ]);

                // Apuntarlo a sí mismo como origen
                $caja->origen()->associate($caja);
                $caja->save();
            });

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El moviiento en caja se creó correctamente.",
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
            // Crear movimiento en caja
            CajaMovimiento::create([
                'monto' => $request->monto,
                'tipo' => $request->tipo, // 'devolucion'
                'motivo' => $request->motivo,
                'fecha' => now(),
                'origen_id' => $nota->id,
                'origen_type' => $request->origen_type,
                'usuario_id' => auth()->id(),
                'activo' => true
            ]);

            // Marcar la nota como inactiva si quieres
            $nota->activo = false;
            $nota->estado = 'DEVUELTO';
            $nota->save();
        });

        return response()->json(['message' => 'Devolución registrada correctamente']);
    }

    public function getSaldoEfectivoDia()
    {
        $hoy = now()->toDateString();

        // Total efectivo de ventas del día
        $ventasEfectivo = TipoPago::where('metodo', 'Efectivo')
            ->where('activo', true)
            ->where('wci', auth()->user()->id)
            ->whereDate('created_at', $hoy)
            ->sum('monto');

        // Entradas del día
        $entradas = CajaMovimiento::where('tipo', 'entrada')
            ->where('activo', true)
            ->where('usuario_id', auth()->user()->id)
            ->whereDate('fecha', $hoy)
            ->sum('monto');

        // Salidas del día
        $salidas = CajaMovimiento::where('tipo', 'salida')
            ->where('activo', true)
            ->where('usuario_id', auth()->user()->id)
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
        // TODO DE CAJA PARA EL INDEX
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
