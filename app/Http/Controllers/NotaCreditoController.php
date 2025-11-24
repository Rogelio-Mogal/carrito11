<?php

namespace App\Http\Controllers;

use App\Models\Garantia;
use App\Models\NotaCredito;
use App\Models\Venta;
use Illuminate\Http\Request;
use PDF;

class NotaCreditoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        return view('nota_credito.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(NotaCredito $notaCredito)
    {
        // Cargar el "notable" (puede ser Venta o Garantia)
        $notable = $notaCredito->notable()->first();

        // Inicializamos variables
        $venta = null;
        $garantia = null;
        $devoluciones = collect();
        $totalDevoluciones = 0;

        if ($notable instanceof Venta) {
            $venta = $notable->load(['detalles.producto', 'pagos']);
            // Ventas donde fue aplicada esta nota
            $ventasAplicadas = $notaCredito->ventasAplicadas()->with('cliente')->get();

            // Todas las devoluciones de esta nota con su detalle y producto
            $devoluciones = $venta->devoluciones()
                ->with(['detalle.producto', 'notaCredito'])
                ->get();

            // Total de todas las devoluciones de esta nota
            $totalDevoluciones = $devoluciones->sum('monto');
        } elseif ($notable instanceof Garantia) {
            $garantia = $notable;
            $ventasAplicadas = collect(); // No aplica

            // Simulamos devoluciones usando la info de la garantía
            $devoluciones = collect([
                (object)[
                    'detalle' => (object)[
                        'producto' => $garantia->producto,
                        'cantidad' => $garantia->cantidad,
                        'precio'   => $garantia->precio_producto,
                    ],
                    'monto'      => $garantia->importe,
                    'motivo'     => $notaCredito->motivo,
                    'notaCredito'=> $notaCredito,
                ]
            ]);

            $totalDevoluciones = $garantia->importe;
        }

        return view('nota_credito.show', compact(
            'notaCredito',
            'venta',
            'garantia',
            'ventasAplicadas',
            'devoluciones',
            'totalDevoluciones'
        ));
    }


    public function show1(NotaCredito $notaCredito)
    {
        // si quieres mandar también la venta origen y sus detalles
        //$venta = $notaCredito->notable()->with(['detalles.producto', 'pagos'])->first();

        //return view('nota_credito.show', compact('notaCredito', 'venta'));

        // Venta original (origen de la nota)
        $venta = $notaCredito->notable()->with(['detalles.producto', 'pagos'])->first();

        // Ventas donde fue aplicada esta nota
        $ventasAplicadas = $notaCredito->ventasAplicadas()->with('cliente')->get();

        // Todas las devoluciones de esta nota con su detalle y producto
        $devoluciones = $venta->devoluciones()
            ->with(['detalle.producto', 'notaCredito'])
            ->get();

        // Total de todas las devoluciones de esta nota
        $totalDevoluciones = $venta->devoluciones()
        ->with('detalle.producto', 'notaCredito')
        ->sum('monto'); // suma del campo 'monto'

        return view('nota_credito.show', compact('notaCredito', 'venta', 'ventasAplicadas', 'devoluciones','totalDevoluciones'));
    }

    public function edit(NotaCredito $notaCredito)
    {
        //
    }

    public function update(Request $request, NotaCredito $notaCredito)
    {
        //
    }

    public function destroy(NotaCredito $notaCredito)
    {
        //
    }

    public function nota_credito_index_ajax(Request $request)
    {
        // TODAS LAS NOTAS DE CRÉDITO PARA INDEX
        if ($request->origen == 'nota.credito.index') {

            $query = NotaCredito::with(['cliente:id,full_name', 'notable']);

            // Aplicar filtro solo si es 0 o 1
            if ($request->has('activo')) {
                if ($request->activo === '1') {
                    $query->where('activo', 1);
                } elseif ($request->activo === '0') {
                    $query->where('activo', 0);
                }
            }

            $creditos = $query->get();

            // Agrupar y mapear
            $data = $creditos->groupBy(function ($nota) {
                return class_basename($nota->notable_type) . '-' . $nota->notable_id;
            })->map(function ($grupoNotas) {
                $notaBase = $grupoNotas->first();
                $modelo   = $notaBase->notable;

                if (!$modelo) return null;

                $totalNotas = $grupoNotas->sum('monto');

                // botones
                $acciones = '';

                // Ver
                //$acciones .= '<a href="'.route('admin.nota.credito.show', $notaBase->id).'" class="btn btn-blue">Ver</a>';
                
                // Ticket
                //$acciones .= '<a href="#" target="_blank" class="btn btn-green">Ticket</a>';

                $acciones .= '
                <a href="'.route('admin.nota.credito.show', $notaBase->id).'" 
                    data-popover-target="ver-tooltip'.$notaBase->id.'" data-popover-placement="bottom"
                    class="mb-1 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                        <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                        </svg>
                        <span class="sr-only">Ver nota de crédito</span>
                </a>
                <div id="ver-tooltip'.$notaBase->id.'" role="tooltip"
                    class="absolute z-10 invisible inline-block w-38 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                    <div class="p-2 space-y-2">
                        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Ver nota de crédito</h6>
                    </div>
                </div>';

                    $acciones .= '
                    <a href="' . route('ticket.nota.credito', $notaBase->id) . '" target="_blank" 
                        data-popover-target="ticket-tooltip'.$notaBase->id.'" data-popover-placement="bottom"
                        class="mb-1 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                            <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-6 4h6m-6 4h6M6 3v18l2-2 2 2 2-2 2 2 2-2 2 2V3l-2 2-2-2-2 2-2-2-2 2-2-2Z"/>
                            </svg>
                            <span class="sr-only">Ticket</span>
                    </a>
                    <div id="ticket-tooltip'.$notaBase->id.'" role="tooltip"
                        class="absolute z-10 invisible inline-block w-38 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                        <div class="p-2 space-y-2">
                            <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Ticket</h6>
                        </div>
                    </div>';

                // Botón Pasar a venta (solo si está activo)
                if ($notaBase->activo) {
                    $ids = $grupoNotas->pluck('id')->join(',');
                    $monto = $totalNotas;
                    $cliente = $notaBase->cliente->full_name ?? '';

                    // Generar URL al create de venta con query params
                    $url = route('admin.ventas.create', [
                        'nota_credito_ids' => $ids,
                        'nota_credito_monto' => $monto,
                        'cliente_id'         => $notaBase->cliente_id,
                        'cliente_nombre' => $cliente
                    ]);

                    $acciones .= '
                        <a href="'.e($url).'" 
                            data-popover-target="venta-tooltip'.$notaBase->id.'" data-popover-placement="bottom"
                            class="mb-1 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                                <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z"/>
                                </svg>
                                <span class="sr-only">Pasar a venta</span>
                        </a>
                        <div id="venta-tooltip'.$notaBase->id.'" role="tooltip"
                            class="absolute z-10 invisible inline-block w-38 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                            <div class="p-2 space-y-2">
                                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Pasar a venta</h6>
                            </div>
                        </div>';

                    $acciones .= '
                        <button type="button" 
                            data-id="'.$notaBase->id.'" data-monto="'.$totalNotas.'"
                            data-popover-target="devolver-tooltip'.$notaBase->id.'" data-popover-placement="bottom"
                            class="mb-1 text-white bg-purple-600 hover:bg-purple-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center devolver-efectivo">
                                <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17.345a4.76 4.76 0 0 0 2.558 1.618c2.274.589 4.512-.446 4.999-2.31.487-1.866-1.273-3.9-3.546-4.49-2.273-.59-4.034-2.623-3.547-4.488.486-1.865 2.724-2.899 4.998-2.31.982.236 1.87.793 2.538 1.592m-3.879 12.171V21m0-18v2.2"/>
                                </svg>

                                <span class="sr-only">Devolver efectivo</span>
                        </button>
                        <div id="devolver-tooltip'.$notaBase->id.'" role="tooltip"
                            class="absolute z-10 invisible inline-block w-38 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                            <div class="p-2 space-y-2">
                                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Devolver efectivo</h6>
                            </div>
                        </div>';
                }

                return [
                    'origen'         => class_basename($modelo::class),
                    'referencia_id'  => $modelo->id,
                    'folio'          => $modelo->folio,
                    'fecha'          => optional($modelo->fecha)->format('Y-m-d'),
                    'cliente_nombre' => $notaBase->cliente->full_name ?? '',
                    'total_monto'    => $totalNotas,
                    'nota_ids'       => $grupoNotas->pluck('id'),
                    'estatus'        => $notaBase->activo ? 'Activo' : 'Inactivo',
                    'notas'          => $grupoNotas->map(function ($nota) {
                        return [
                            'id'     => $nota->id,
                            'monto'  => $nota->monto,
                            'motivo' => $nota->motivo,
                            'tipo'   => $nota->tipo,
                        ];
                    })->values(),
                    'acciones' => $acciones,
                ];
            })->filter()->values();

            return response()->json(['data' => $data]);
        }

        // TODAS LAS NOTAS DE CRÉDITO ACTIVAS PARA PARA VENTAS
        if ($request->origen == 'nota.credito.ventas') {
            /*$creditos = NotaCredito::with(['cliente:id,full_name', 'notable'])
                ->where('activo', true)    
                ->get(); // Trae todas las notas, sin filtrar

            // Agrupar por el modelo y su id (por ejemplo, Venta-10)
            $data = $creditos->groupBy(function ($nota) {
                return class_basename($nota->notable_type) . '-' . $nota->notable_id;
            })->map(function ($grupoNotas) {
                $notaBase = $grupoNotas->first();
                $modelo   = $notaBase->notable;

                if (!$modelo) return null;

                // Puedes sumar todos los montos de las notas de la venta
                $totalNotas = $grupoNotas->sum('monto');

                return [
                    'origen'         => class_basename($modelo::class), // Ej: Venta
                    'referencia_id'  => $modelo->id,
                    'fecha'          => optional($modelo->fecha)->format('Y-m-d'),
                    'cliente_nombre' => $notaBase->cliente->full_name ?? '',
                    'total_monto'    => $totalNotas,
                    'nota_ids'       => $grupoNotas->pluck('id'), // <-- IDs de todas las notas del grupo
                    'notas'          => $grupoNotas->map(function ($nota) {
                        return [
                            'id'     => $nota->id,
                            'monto'  => $nota->monto,
                            'motivo' => $nota->motivo,
                            'tipo'   => $nota->tipo,
                        ];
                    })->values(),
                ];
            })->filter()->values();*/

            $creditos = NotaCredito::with(['cliente:id,full_name', 'notable'])
                ->where('activo', true)
                ->get();

            $data = $creditos->groupBy(function ($nota) {
                return class_basename($nota->notable_type) . '-' . $nota->notable_id;
            })->map(function ($grupoNotas) {
                $notaBase = $grupoNotas->first();
                $modelo   = $notaBase->notable;

                if (!$modelo) return null;

                // Fecha común para Venta o Garantía
                //$fecha = optional($modelo->fecha)->format('Y-m-d');
                $fecha = optional($notaBase->created_at)->format('Y-m-d H:m:s');


                // Sumar todos los montos de las notas del grupo
                $totalNotas = $grupoNotas->sum('monto');

                return [
                    'origen'         => class_basename($modelo::class), // Ej: Venta o Garantia
                    'referencia_id'  => $modelo->id,
                    'fecha'          => $fecha,
                    'cliente_nombre' => $notaBase->cliente->full_name ?? '',
                    'total_monto'    => $totalNotas,
                    'nota_ids'       => $grupoNotas->pluck('id'),
                    'notas'          => $grupoNotas->map(fn($nota) => [
                        'id'     => $nota->id,
                        'monto'  => $nota->monto,
                        'motivo' => $nota->motivo,
                        'tipo'   => $nota->tipo,
                    ])->values(),
                ];
            })->filter()->values();

            return response()->json(['data' => $data]);
        }

    }

    public function ticket($id){
        $nota = NotaCredito::with([
            'cliente:id,full_name',
            'ventasAplicadas:id,folio,total,created_at,fecha',
            'ventaDevoluciones.detalle.producto'
        ])->findOrFail($id);

        //  - CREAMOS EL PDF DE LA VENTA ----
        $user = auth()->user();
        $userPrinterSize = 80;

        $size = match($userPrinterSize) {
            58 => [0,0,140,1440],
            80 => [0,0,212,1440],
            default => [0,0,0,0],
        };

        $pdf = PDF::loadView('comprobantes.ticket_nota_credito', compact('nota','userPrinterSize','user'))
            ->setPaper($size,'portrait');
        return $pdf->stream();
    }
}
