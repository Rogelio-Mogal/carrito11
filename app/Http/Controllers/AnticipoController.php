<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\AnticipoApartado;
use App\Models\AnticipoApartadoDetalle;
use App\Models\Cliente;
use App\Models\DetalleAbono;
use App\Models\TipoPago;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;

class AnticipoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:anticipo.ver')
        ->only(['index', 'show']);

        $this->middleware('permission:anticipo.crear')
            ->only(['create', 'store']);

        $this->middleware('permission:anticipo.editar')
            ->only(['edit', 'update']);

        $this->middleware('permission:anticipo.eliminar')
            ->only(['destroy']);
    }

    public function index()
    {
        $now = new \DateTime();
        return view('anticipo.index', compact('now'));
    }

    public function create()
    {
        $anticipoApartado = new AnticipoApartado();
        $anticipoApartado->cliente_id = 1; // CLIENTE PÚBLICO por defecto
        $metodo = 'create';
        $detalle = collect();

        $tipoValues = ['CLIENTE PÚBLICO', 'CLIENTE MEDIO MAYOREO', 'CLIENTE MAYOREO'];
        $ejecutivoValues = User::where('tipo_usuario', 'punto_de_venta')
            ->where('activo', 1)
            ->select('id', 'full_name')
            ->get();


        $formasPago = [
            ['metodo' => '', 'monto' => '', 'referencia' => '']
        ];

        return view('anticipo.create', compact(
            'metodo',
            'anticipoApartado',
            'detalle',
            'tipoValues',
            'ejecutivoValues'
        ));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $anioActual = Carbon::now()->year;

            // Buscar el último folio del año actual
            $ultimo = AnticipoApartado::whereYear('fecha', $anioActual)
                ->where('tipo', 'ANTICIPO')
                ->lockForUpdate()
                ->orderByDesc('id')
                ->value('folio');

            $ultimoNumero = 0;
            if ($ultimo && preg_match('/ANTICIPO-(\d+)-' . $anioActual . '/', $ultimo, $match)) {
                $ultimoNumero = intval($match[1]);
            }

            $nuevoNumero = $ultimoNumero + 1;
            $folio = sprintf("ANTICIPO-%05d-%d", $nuevoNumero, $anioActual);

            // ==============================
            // 1. Crear anticipo_apartado
            // ==============================
            $anticipoApartado = AnticipoApartado::create([
                'fecha'      => now(),
                'cliente_id' => $request->cliente_id,
                'tipo'       => 'ANTICIPO',
                'folio'      => $folio,
                'total'      => $request->total,
                'debia'      => $request->total,
                'abona'      => 0,
                'debe'       => $request->total,
                'estatus'    => 'ACTIVO',
                'wci'        => auth()->id(),
            ]);

            // ==============================
            // 2. Crear detalle
            // ==============================
            AnticipoApartadoDetalle::create([
                'anticipo_apartado_id' => $anticipoApartado->id,
                'producto_id'    => $request->producto_id ?? null,
                'producto_comun' => $request->producto_comun,
                'cantidad'       => $request->cantidad,
                'precio'         => $request->total, // o unitario si lo manejas
                'total'          => $request->total,
                'wci'            => auth()->id(),
            ]);

            // ==============================
            // 3. Registrar formas de pago
            // ==============================
            $totalAbono = 0;
            foreach ($request->formas_pago as $forma) {
                if (!empty($forma['monto']) && $forma['monto'] > 0) {
                    TipoPago::create([
                        'pagable_id'   => $anticipoApartado->id,
                        'pagable_type' => AnticipoApartado::class,
                        'metodo'       => $forma['metodo'],
                        'monto'        => $forma['monto'],
                        'referencia'   => $forma['referencia'] ?? null,
                        'wci'          => auth()->id(),
                        'activo'       => true,
                    ]);
                    $totalAbono += $forma['monto'];
                }
            }

            // ==============================
            // 4. Crear abono inicial
            // ==============================
            if ($totalAbono > 0) {
                // Obtener el último folio de abonos de este año
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

                $abono = Abono::create([
                    'folio'              => $folioAbono,
                    'fecha'              => now(),
                    'abonable_id'        => $anticipoApartado->id,
                    'abonable_type'      => AnticipoApartado::class,
                    'cliente_id'         => $request->cliente_id,
                    'monto'              => $totalAbono,
                    'saldo_global_antes' => $anticipoApartado->debia,
                    'saldo_global_despues' => $anticipoApartado->debia - $totalAbono,
                    'activo'             => true,
                    'wci'                => auth()->id(),
                ]);

                // Detalle del abono (específico al anticipo)
                DetalleAbono::create([
                    'abono_id'      => $abono->id,
                    'venta_credito_id' => null, // porque no es venta crédito
                    'abonado_a_id'  => $anticipoApartado->id,
                    'abonado_a_type' => AnticipoApartado::class,
                    'monto_antes'   => $anticipoApartado->debia,
                    'abonado'       => $totalAbono,
                    'saldo_despues' => $anticipoApartado->debia - $totalAbono,
                    'activo'        => true,
                ]);

                // ==============================
                // 5. Actualizar anticipo
                // ==============================
                $anticipoApartado->update([
                    'abona' => $totalAbono,
                    'debe'  => $anticipoApartado->debia - $totalAbono,
                ]);
            }

            DB::commit();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Anticipo registrado",
                'text' => "El anticipo se registró correctamente con folio {$folio}.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.anticipo.index')->with(['id' => $anticipoApartado->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($clienteId)
    {
        $cliente = Cliente::findOrFail($clienteId);

        $anticipos = AnticipoApartado::with(['detalles', 'cliente'])
        ->where('tipo','ANTICIPO')
        ->where('cliente_id', $clienteId)
        ->where('debe', '>', 0) // solo los pendientes
        ->orderByDesc('fecha')
        ->get();

        // Abonos de este cliente
        //$abonos = $anticipos->pluck('abonos')->flatten(1);

        $abonos = Abono::with([
        'detalles.abonado_a', // para obtener cada anticipo al que se aplicó el detalle
        'abonable',           // relación morphTo para el anticipo
        'user'
        ])
        ->where('cliente_id', $clienteId)
        ->where('activo', 1)
        ->where('abonable_type', AnticipoApartado::class) // Solo abonos a anticipos
        ->orderByDesc('fecha')
        ->get();

        return view('anticipo.show', compact('cliente', 'anticipos', 'abonos'));
    }

    public function edit(AnticipoApartado $anticipoApartado)
    {
        //
    }

    public function update(Request $request, AnticipoApartado $anticipoApartado)
    {
        //
    }

    public function destroy(AnticipoApartado $anticipoApartado)
    {
        //
    }

    public function anticipo_index_ajax(Request $request)
    {
        // TODOS LOS ANTICIPOS PARA INDEX
        if ($request->origen == 'anticipo.apartado.index') {

            $filtro = $request->input('filtro')
            ?? $request->input('mes_hidden')
            ?? $request->input('rango');
            $mes          = $request->input('mes');          // '2026-01'
            $fechaInicio  = $request->input('fechaInicio');  // '2026-01-01'
            $fechaFin     = $request->input('fechaFin');     // '2026-01-31'

            $anticipoQuery = AnticipoApartado::select(
                'cliente_id',
                \DB::raw('SUM(debia) as total_debia'),
                \DB::raw('SUM(abona) as total_abona'),
                \DB::raw('SUM(debe) as total_debe')
            )
            ->where('tipo','ANTICIPO')
            ->with('cliente') // carga el cliente
            ->groupBy('cliente_id');

            if ($filtro === 'MES' && filled($mes)) {
                $fecha = Carbon::createFromFormat('Y-m', $mes);

                $anticipoQuery
                    ->whereYear('fecha', $fecha->year)
                    ->whereMonth('fecha', $fecha->month);
            }

            if ($filtro === 'RANGO' && $fechaInicio && $fechaFin) {
                $anticipoQuery->whereBetween('fecha', [
                    Carbon::parse($fechaInicio)->startOfDay(),
                    Carbon::parse($fechaFin)->endOfDay(),
                ]);
            }

            if (!$filtro) {
                $anticipoQuery
                    ->whereMonth('fecha', now()->month)
                    ->whereYear('fecha', now()->year);
            }

            $data = $anticipoQuery
            ->get()
            ->map(function ($item) {
                $ultimoAnticipo = AnticipoApartado::where('cliente_id', $item->cliente_id)
                ->where('tipo','ANTICIPO')
                ->orderByDesc('id')
                ->first();
                return [
                    'cliente_id'   => $item->cliente_id,
                    'cliente'      => $item->cliente?->full_name ?? 'Sin cliente',
                    'total_credito'=> $item->total_debia,   // total debia como "Monto"
                    'total_saldo'  => $item->total_debe,    // total debe como "Saldo actual"
                    'estatus'      => $ultimoAnticipo?->estatus ?? 'N/A',
                ];
            });




            /*
            $anticipoPorCliente = AnticipoApartado::select(
                'cliente_id',
                \DB::raw('SUM(debia) as total_debia'),
                \DB::raw('SUM(abona) as total_abona'),
                \DB::raw('SUM(debe) as total_debe')
            )
            ->where('tipo','ANTICIPO')
            ->with('cliente') // carga el cliente
            ->groupBy('cliente_id')
            ->get();

            // Retornar los datos con nombres que coincidan con los columnas DataTables
            $data = $anticipoPorCliente->map(function($item) {
                $ultimoAnticipo = AnticipoApartado::where('cliente_id', $item->cliente_id)
                ->where('tipo','ANTICIPO')
                ->orderByDesc('id')
                ->first();
                return [
                    'cliente_id'   => $item->cliente_id,
                    'cliente'      => $item->cliente?->full_name ?? 'Sin cliente',
                    'total_credito'=> $item->total_debia,   // total debia como "Monto"
                    'total_saldo'  => $item->total_debe,    // total debe como "Saldo actual"
                    'estatus'      => $ultimoAnticipo?->estatus ?? 'N/A',
                ];
            });
            */

            return response()->json(['data' => $data]);
        }

        // TODOS LOS ANTICIPOS PARA LA VENTA
        if ($request->origen == 'anticipo.apartado.ventas') {

            /*
            $anticipos = AnticipoApartado::with('cliente')
            ->whereIn('estatus', ['ACTIVO', 'LIQUIDADO'])
            ->get();

            $data = $anticipos->map(function($item) {
                return [
                    'fecha'        => $item->fecha,
                    'folio'        => $item->folio,
                    'cliente_id'   => $item->cliente_id,
                    'cliente'      => $item->cliente?->full_name ?? 'Sin cliente',
                    'total_anticipo_apartado' => $item->debia, // ya no sumatoria, es por registro
                    'total_abono'  => $item->abona,
                    'total_saldo'  => $item->debe,
                    'anticipo_apartado_id'    => $item->id
                ];
            });
            */

            $anticipos = AnticipoApartado::with(['cliente', 'detalles.producto'])
                ->whereIn('estatus', ['ACTIVO', 'LIQUIDADO'])
                ->get();

            $data = $anticipos->map(function($item) {
                return [
                    'fecha'        => $item->fecha,
                    'folio'        => $item->folio,
                    'cliente_id'   => $item->cliente_id,
                    'cliente'      => $item->cliente?->full_name ?? 'Sin cliente',
                    'total_anticipo_apartado' => $item->debia,
                    'total_abono'  => $item->abona,
                    'total_saldo'  => $item->debe,
                    'anticipo_apartado_id'    => $item->id,
                    'tipo'    => $item->tipo,
                    'productos'    => $item->detalles->map(function($d) {
                        return [
                            'producto_id'   => $d->producto_id,
                            'nombre'        => $d->producto?->nombre ?? 'Sin nombre',
                            'cantidad'      => $d->cantidad,
                            'precio'        => $d->precio,
                            'total'         => $d->total,
                        ];
                    })
                ];
            });

            return response()->json(['data' => $data]);
        }
    }

    public function ticket($id){
        $anticipo = AnticipoApartado::with([
            'cliente:id,full_name',
            'detalles.producto:id,nombre',
            'abonos',
            'venta:id,folio'
        ])->findOrFail($id);

        //  - CREAMOS EL PDF DE LA VENTA ----
        $user = auth()->user();
        $userPrinterSize = 80;

        $size = match($userPrinterSize) {
            58 => [0,0,140,1440],
            80 => [0,0,212,1440],
            default => [0,0,0,0],
        };

        $pdf = PDF::loadView('comprobantes.ticket_anticipo', compact('anticipo','userPrinterSize','user'))
            ->setPaper($size,'portrait');
        return $pdf->stream();
    }
}
