<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\Cliente;
use App\Models\VentaCredito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaCreditoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:venta_credito.ver')
        ->only(['index', 'show']);

        $this->middleware('permission:venta_credito.crear')
            ->only(['create', 'store']);

        $this->middleware('permission:venta_credito.editar')
            ->only(['edit', 'update']);

        $this->middleware('permission:venta_credito.cancelar')
            ->only(['destroy']);
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
        $creditos = VentaCredito::with(['venta.cliente'])
        ->get()
        ->groupBy('venta.cliente.id')
        ->map(function($rows) {
            return [
                'cliente_id' => $rows->first()->venta->cliente->id,
                'cliente' => $rows->first()->venta->cliente->full_name,
                'total_credito' => $rows->sum('monto_credito'),
                'total_saldo'   => $rows->sum('saldo_actual'),
            ];
        })
        ->values(); // Para resetear índices numéricos

            return response()->json(['data' => $creditos]);
        }
        return view('venta_credito.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($clienteId)
    {
        $cliente = Cliente::findOrFail($clienteId);

        // Ventas a crédito pendientes de ese cliente
        /*$ventasCredito = VentaCredito::with(['venta.cliente'])
            ->whereHas('venta', fn($q) => $q->where('cliente_id', $clienteId))
            ->where('saldo_actual', '>', 0)
            ->orderByDesc('venta.fecha')
            ->get();*/

        $ventasCredito = VentaCredito::select('venta_creditos.*')
        ->join('ventas', 'venta_creditos.venta_id', '=', 'ventas.id')
        ->where('ventas.cliente_id', $clienteId)
        ->where('saldo_actual', '>', 0)
        ->orderByDesc('ventas.fecha')
        ->get();

        // Abonos de este cliente
        $abonos = Abono::with([
                'detalles.ventaCredito.venta',
                'abonable',
                'user'
            ])
            ->where('cliente_id', $clienteId)
            ->where('activo', 1)
            ->orderByDesc('fecha')
            ->get();

        return view('venta_credito.show', compact('cliente', 'ventasCredito', 'abonos'));
    }

    public function edit(VentaCredito $ventaCredito)
    {
        //
    }

    public function update(Request $request, VentaCredito $ventaCredito)
    {
        //
    }

    public function destroy(VentaCredito $ventaCredito)
    {
        //
    }

    public function productos_index_ajax(Request $request)
    {
        // TODOS LOS PRODUCTOS PARA EL INDEX DE LA TABLA PRODUCTOS/SERVICIOS
        if ($request->origen == 'venta.credito.index') {
            $creditos = VentaCredito::get();

            return response()->json(['data' => $creditos]);
        }






    }
}
