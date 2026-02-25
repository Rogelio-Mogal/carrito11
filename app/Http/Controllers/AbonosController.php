<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\AnticipoApartado;
use App\Models\Cliente;
use App\Models\VentaCredito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

class AbonosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo_abono' => 'required|in:venta,monto',
            'venta_credito_id' => 'nullable|exists:venta_creditos,id',
            'formas_pago' => 'required|array',
            'formas_pago.*.metodo' => 'required|string',
            'formas_pago.*.monto' => 'nullable|numeric|min:0',
            'referencia' => 'nullable|string|max:255',
        ]);

        $cliente = Cliente::findOrFail($request->cliente_id);

        // Calcular monto total del abono sumando todas las formas de pago
        $montoAbono = collect($request->formas_pago)->sum(fn($p) => (float)$p['monto']);

        if ($montoAbono <= 0) {
            return redirect()->back()->withErrors(['formas_pago' => 'El monto total del abono debe ser mayor a cero.']);
        }

        // Validaciones de negocio
        if ($request->tipo_abono === 'venta' && $request->venta_credito_id) {
            $ventaCredito = VentaCredito::findOrFail($request->venta_credito_id);
            if ($montoAbono > $ventaCredito->saldo_actual) {
                return redirect()->back()->withErrors([
                    'formas_pago' => "El monto del abono no puede ser mayor al saldo actual de la venta ({$ventaCredito->saldo_actual})."
                ]);
            }
        } elseif ($request->tipo_abono === 'monto') {
            $saldoGlobal = $cliente->ventaCreditos()->where('saldo_actual', '>', 0)->sum('saldo_actual');
            if ($montoAbono > $saldoGlobal) {
                return redirect()->back()->withErrors([
                    'formas_pago' => "El monto total del abono no puede ser mayor al saldo global de todas las ventas pendientes ({$saldoGlobal})."
                ]);
            }
        }

        try {
            $abono = DB::transaction(function () use ($request, $cliente, $montoAbono) {

                // Saldo global antes del abono
                $saldoGlobalAntes = $cliente->ventaCreditos()
                    ->where('saldo_actual', '>', 0)
                    ->sum('saldo_actual');

                // Obtener el último folio de abonos de este año
                $anioActual = Carbon::now()->year;
                $ultimoAbono = Abono::whereYear('created_at', $anioActual)
                    ->lockForUpdate() // bloquea filas de abonos mientras corre la transacción
                    ->orderByDesc('id')
                    ->value('folio');

                $ultimoNumeroAbono = 0;
                if ($ultimoAbono && preg_match('/ABO-(\d+)-' . $anioActual . '/', $ultimoAbono, $match)) {
                    $ultimoNumeroAbono = intval($match[1]);
                }

                $nuevoNumero = $ultimoNumeroAbono + 1;
                $folioAbono = sprintf("ABO-%05d-%d", $nuevoNumero, $anioActual);

                $ventaCredito = VentaCredito::findOrFail($request->venta_credito_id);

                $abono = Abono::create([
                    'folio' => $folioAbono,
                    'fecha' => now(),
                    'abonable_type' => VentaCredito::class,
                    'abonable_id' => $ventaCredito->id, //$cliente->id,
                    'cliente_id' => $cliente->id,
                    'monto' => $montoAbono,
                    'saldo_global_antes' => $saldoGlobalAntes,
                    'saldo_global_despues' => $saldoGlobalAntes, // se actualizará después
                    'referencia' => $request->referencia ?? null,
                    'activo' => 1,
                    'wci' => 1,
                ]);

                // Aplicar abono a ventas
                if ($request->tipo_abono === 'venta' && $request->venta_credito_id) {
                    $ventaCredito = VentaCredito::findOrFail($request->venta_credito_id);
                    $abonado = min($montoAbono, $ventaCredito->saldo_actual);

                    $abono->detalles()->create([
                        'venta_credito_id' => $ventaCredito->id,
                        'abonado_a_type' => VentaCredito::class,
                        'abonado_a_id' => $ventaCredito->id,
                        'monto_antes' => $ventaCredito->saldo_actual,
                        'abonado' => $abonado,
                        'saldo_despues' => $ventaCredito->saldo_actual - $abonado,
                        'activo' => 1,
                    ]);

                    $ventaCredito->saldo_actual -= $abonado;
                    if ($ventaCredito->saldo_actual <= 0) $ventaCredito->liquidado = 1;
                    $ventaCredito->save();
                } elseif ($request->tipo_abono === 'monto') {
                    $ventasPendientes = $cliente->ventaCreditos()
                        ->where('saldo_actual', '>', 0)
                        ->with('venta')
                        ->get()
                        ->sortBy(function ($vc) {
                            return $vc->venta->fecha; // ordena por fecha de venta
                        });

                    foreach ($ventasPendientes as $venta) {
                        if ($montoAbono <= 0) break;

                        $abonado = min($montoAbono, $venta->saldo_actual);

                        $abono->detalles()->create([
                            'venta_credito_id' => $venta->id,
                            'abonado_a_type' => VentaCredito::class,
                            'abonado_a_id' => $venta->id,
                            'monto_antes' => $venta->saldo_actual,
                            'abonado' => $abonado,
                            'saldo_despues' => $venta->saldo_actual - $abonado,
                            'activo' => 1,
                        ]);

                        $venta->saldo_actual -= $abonado;
                        if ($venta->saldo_actual <= 0) $venta->liquidado = 1;
                        $venta->save();

                        $montoAbono -= $abonado;
                    }
                }

                // Actualizar saldo global después
                $abono->saldo_global_despues = $cliente->ventaCreditos()
                    ->where('saldo_actual', '>', 0)
                    ->sum('saldo_actual');
                $abono->save();

                // Registrar formas de pago (TipoPago)
                foreach ($request->formas_pago as $pago) {
                    if ((float)$pago['monto'] > 0) {
                        $abono->pagos()->create([
                            'metodo' => $pago['metodo'],
                            'monto' => $pago['monto'],
                            'activo' => 1,
                            'wci' => 1,
                        ]);
                    }
                }

                return $abono;
            });

            return redirect()->back()
            ->with('success', 'Abono registrado correctamente.')
            ->with('id', $abono->id);

            return redirect()->back()->with('success', 'Abono registrado correctamente.');
        } catch (\Exception $e) {
            // Log opcional: \Log::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al registrar el abono.']);
        }
    }

    public function storeAnticipo(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo_abono' => 'required|in:anticipo,monto', // anticipo individual o por monto
            'anticipo_id' => 'nullable|exists:anticipo_apartados,id',
            'formas_pago' => 'required|array',
            'formas_pago.*.metodo' => 'required|string',
            'formas_pago.*.monto' => 'nullable|numeric|min:0',
            'referencia' => 'nullable|string|max:255',
        ]);

        $cliente = Cliente::findOrFail($request->cliente_id);

        $montoAbono = collect($request->formas_pago)->sum(fn($p) => (float)$p['monto']);
        if ($montoAbono <= 0) {
            return redirect()->back()->withErrors(['formas_pago' => 'El monto total del abono debe ser mayor a cero.']);
        }

        // Validaciones de negocio
        $abonableId = null;
        if ($request->tipo_abono === 'anticipo' && $request->anticipo_id) {
            $anticipo = AnticipoApartado::findOrFail($request->anticipo_id);
            $abonableId = $anticipo->id;
            if ($montoAbono > $anticipo->debe) {
                return redirect()->back()->withErrors([
                    'formas_pago' => "El monto del abono no puede ser mayor al saldo pendiente del anticipo ({$anticipo->debe})."
                ]);
            }
        } elseif ($request->tipo_abono === 'monto') {
            $saldoGlobal = $cliente->anticiposApartados()->where('debe', '>', 0)->sum('debe');
            if ($montoAbono > $saldoGlobal) {
                return redirect()->back()->withErrors([
                    'formas_pago' => "El monto total del abono no puede ser mayor al saldo global de todos los anticipos pendientes ({$saldoGlobal})."
                ]);
            }
        }

        try {
            $abono = DB::transaction(function () use ($request, $cliente, $montoAbono, $abonableId) {

                $saldoGlobalAntes = $cliente->anticiposApartados()->where('debe', '>', 0)->sum('debe');

                // Generar folio de abono
                $anioActual = Carbon::now()->year;
                $ultimoAbono = Abono::whereYear('created_at', $anioActual)
                    ->lockForUpdate()
                    ->orderByDesc('id')
                    ->value('folio');

                $ultimoNumeroAbono = 0;
                if ($ultimoAbono && preg_match('/ABO-(\d+)-' . $anioActual . '/', $ultimoAbono, $match)) {
                    $ultimoNumeroAbono = intval($match[1]);
                }
                $folioAbono = sprintf("ABO-%05d-%d", $ultimoNumeroAbono + 1, $anioActual);

                $abono = Abono::create([
                    'folio' => $folioAbono,
                    'fecha' => now(),
                    'abonable_type' => AnticipoApartado::class,
                    'abonable_id' => $abonableId,
                    'cliente_id' => $cliente->id,
                    'monto' => $montoAbono,
                    'saldo_global_antes' => $saldoGlobalAntes,
                    'saldo_global_despues' => $saldoGlobalAntes, // se actualizará después
                    'referencia' => $request->referencia ?? null,
                    'activo' => 1,
                    'wci' => 1,
                ]);

                // Aplicar abono
                if ($request->tipo_abono === 'anticipo' && $request->anticipo_id) {
                    $anticipo = AnticipoApartado::findOrFail($request->anticipo_id);

                    $abonado = min($montoAbono, $anticipo->debe);

                    $abono->detalles()->create([
                        'abonado_a_type' => AnticipoApartado::class,
                        'abonado_a_id' => $anticipo->id,
                        'monto_antes' => $anticipo->debe,
                        'abonado' => $abonado,
                        'saldo_despues' => $anticipo->debe - $abonado,
                        'activo' => 1,
                    ]);

                    $anticipo->abona += $abonado;
                    $anticipo->debe -= $abonado;
                    if ($anticipo->debe <= 0) $anticipo->estatus = 'liquidado';
                    $anticipo->save();
                } elseif ($request->tipo_abono === 'monto') {
                    $anticiposPendientes = $cliente->anticiposApartados()
                        ->where('debe', '>', 0)
                        ->orderBy('fecha') // del más antiguo al más reciente
                        ->get();

                    foreach ($anticiposPendientes as $anticipo) {
                        if ($montoAbono <= 0) break;

                        $abonado = min($montoAbono, $anticipo->debe);

                        $abono->detalles()->create([
                            'abonado_a_type' => AnticipoApartado::class,
                            'abonado_a_id' => $anticipo->id,
                            'monto_antes' => $anticipo->debe,
                            'abonado' => $abonado,
                            'saldo_despues' => $anticipo->debe - $abonado,
                            'activo' => 1,
                        ]);

                        $anticipo->abona += $abonado;
                        $anticipo->debe -= $abonado;
                        if ($anticipo->debe <= 0) $anticipo->estatus = 'liquidado';
                        $anticipo->save();

                        $montoAbono -= $abonado;
                    }
                }

                // Actualizar saldo global después
                $abono->saldo_global_despues = $cliente->anticiposApartados()->where('debe', '>', 0)->sum('debe');
                $abono->save();

                // Registrar formas de pago
                foreach ($request->formas_pago as $pago) {
                    if ((float)$pago['monto'] > 0) {
                        $abono->pagos()->create([
                            'metodo' => $pago['metodo'],
                            'monto' => $pago['monto'],
                            'activo' => 1,
                            'wci' => 1,
                        ]);
                    }
                }

                return $abono;
            });

            return redirect()->back()
            ->with('success', 'Abono al anticipo registrado correctamente.')
            ->with('id', $abono->id);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al registrar el abono.']);
        }
    }

    public function storeApartado(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo_abono' => 'required|in:anticipo,monto', // anticipo individual o por monto
            'anticipo_id' => 'nullable|exists:anticipo_apartados,id',
            'formas_pago' => 'required|array',
            'formas_pago.*.metodo' => 'required|string',
            'formas_pago.*.monto' => 'nullable|numeric|min:0',
            'referencia' => 'nullable|string|max:255',
        ]);

        $cliente = Cliente::findOrFail($request->cliente_id);

        $montoAbono = collect($request->formas_pago)->sum(fn($p) => (float)$p['monto']);
        if ($montoAbono <= 0) {
            return redirect()->back()->withErrors(['formas_pago' => 'El monto total del abono debe ser mayor a cero.']);
        }

        // Validaciones de negocio
        $abonableId = null;
        if ($request->tipo_abono === 'anticipo' && $request->anticipo_id) {
            $anticipo = AnticipoApartado::findOrFail($request->anticipo_id);
            $abonableId = $anticipo->id;
            if ($montoAbono > $anticipo->debe) {
                return redirect()->back()->withErrors([
                    'formas_pago' => "El monto del abono no puede ser mayor al saldo pendiente del apartado ({$anticipo->debe})."
                ]);
            }
        } elseif ($request->tipo_abono === 'monto') {
            $saldoGlobal = $cliente->anticiposApartados()->where('debe', '>', 0)->sum('debe');
            if ($montoAbono > $saldoGlobal) {
                return redirect()->back()->withErrors([
                    'formas_pago' => "El monto total del abono no puede ser mayor al saldo global de todos los apartados pendientes ({$saldoGlobal})."
                ]);
            }
        }

        try {
            $abono = DB::transaction(function () use ($request, $cliente, $montoAbono, $abonableId) {

                $saldoGlobalAntes = $cliente->anticiposApartados()->where('debe', '>', 0)->sum('debe');

                // Generar folio de abono
                $anioActual = Carbon::now()->year;
                $ultimoAbono = Abono::whereYear('created_at', $anioActual)
                    ->lockForUpdate()
                    ->orderByDesc('id')
                    ->value('folio');

                $ultimoNumeroAbono = 0;
                if ($ultimoAbono && preg_match('/ABO-(\d+)-' . $anioActual . '/', $ultimoAbono, $match)) {
                    $ultimoNumeroAbono = intval($match[1]);
                }
                $folioAbono = sprintf("ABO-%05d-%d", $ultimoNumeroAbono + 1, $anioActual);

                $abono = Abono::create([
                    'folio' => $folioAbono,
                    'fecha' => now(),
                    'abonable_type' => AnticipoApartado::class,
                    'abonable_id' => $abonableId,
                    'cliente_id' => $cliente->id,
                    'monto' => $montoAbono,
                    'saldo_global_antes' => $saldoGlobalAntes,
                    'saldo_global_despues' => $saldoGlobalAntes, // se actualizará después
                    'referencia' => $request->referencia ?? null,
                    'activo' => 1,
                    'wci' => 1,
                ]);

                // Aplicar abono
                if ($request->tipo_abono === 'anticipo' && $request->anticipo_id) {
                    $anticipo = AnticipoApartado::findOrFail($request->anticipo_id);

                    $abonado = min($montoAbono, $anticipo->debe);

                    $abono->detalles()->create([
                        'abonado_a_type' => AnticipoApartado::class,
                        'abonado_a_id' => $anticipo->id,
                        'monto_antes' => $anticipo->debe,
                        'abonado' => $abonado,
                        'saldo_despues' => $anticipo->debe - $abonado,
                        'activo' => 1,
                    ]);

                    $anticipo->abona += $abonado;
                    $anticipo->debe -= $abonado;
                    if ($anticipo->debe <= 0) $anticipo->estatus = 'liquidado';
                    $anticipo->save();
                } elseif ($request->tipo_abono === 'monto') {
                    $anticiposPendientes = $cliente->anticiposApartados()
                        ->where('debe', '>', 0)
                        ->orderBy('fecha') // del más antiguo al más reciente
                        ->get();

                    foreach ($anticiposPendientes as $anticipo) {
                        if ($montoAbono <= 0) break;

                        $abonado = min($montoAbono, $anticipo->debe);

                        $abono->detalles()->create([
                            'abonado_a_type' => AnticipoApartado::class,
                            'abonado_a_id' => $anticipo->id,
                            'monto_antes' => $anticipo->debe,
                            'abonado' => $abonado,
                            'saldo_despues' => $anticipo->debe - $abonado,
                            'activo' => 1,
                        ]);

                        $anticipo->abona += $abonado;
                        $anticipo->debe -= $abonado;
                        if ($anticipo->debe <= 0) $anticipo->estatus = 'liquidado';
                        $anticipo->save();

                        $montoAbono -= $abonado;
                    }
                }

                // Actualizar saldo global después
                $abono->saldo_global_despues = $cliente->anticiposApartados()->where('debe', '>', 0)->sum('debe');
                $abono->save();

                // Registrar formas de pago
                foreach ($request->formas_pago as $pago) {
                    if ((float)$pago['monto'] > 0) {
                        $abono->pagos()->create([
                            'metodo' => $pago['metodo'],
                            'monto' => $pago['monto'],
                            'activo' => 1,
                            'wci' => 1,
                        ]);
                    }
                }
                return $abono;
            });

            return redirect()->back()
            ->with('success', 'Abono al apartado registrado correctamente.')
            ->with('id', $abono->id);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al registrar el abono.']);
        }
    }

    public function show(Abono $abono)
    {
        //
    }

    public function edit(Abono $abono)
    {
        //
    }

    public function update(Request $request, Abono $abono)
    {
        //
    }

    public function destroy(Abono $abono)
    {
        //
    }

    public function ticket($id){

        //  - CREAMOS EL PDF DE LA VENTA ----
        $user = auth()->user();
        $userPrinterSize = 80;

        $size = match($userPrinterSize) {
            58 => [0,0,140,1440],
            80 => [0,0,212,1440],
            default => [0,0,0,0],
        };

        // 🔹 Traemos el abono con todas sus relaciones
        $abono = Abono::with([
            'cliente',
            'pagos',      // formas de pago
            'detalles.abonado_a.pagos', // pagos de Venta o AnticipoApartado
            'user'
        ])->findOrFail($id);

        $pdf = PDF::loadView('comprobantes.ticket_abono', compact('abono','userPrinterSize','user'))
            ->setPaper($size,'portrait');
        return $pdf->stream();
    }
}
