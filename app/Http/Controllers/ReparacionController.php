<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\Reparacion;
use App\Models\ReparacionProducto;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;

class ReparacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        return view('reparacion.index');
    }

    public function create()
    {
        $reparacion = new Reparacion();
        $reparacion->cliente_id = 1; // CLIENTE PÚBLICO por defecto
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

        return view('reparacion.create', compact(
            'metodo',
            'reparacion',
            'detalle',
            'tipoValues',
            'ejecutivoValues'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipo' => 'required|string|max:255',
            'fallo'  => 'required|string',
            'cliente_id' => 'required|exists:clientes,id', // opcional si quieres validar cliente
        ]);

        try {

            $reparacion = new Reparacion();
            // Obtener año actual
            $anioActual = Carbon::now()->year;

            // Buscar el último folio del año actual
            $ultimo = Reparacion::whereYear('fecha_ingreso', $anioActual)
                ->lockForUpdate() // 🔒 bloquea filas de ventas de este año mientras corre la transacción
                ->orderByDesc('id')
                ->value('folio');

            $ultimoNumero = 0;
            if ($ultimo && preg_match('/SERVICIO-(\d+)-' . $anioActual . '/', $ultimo, $match)) {
                $ultimoNumero = intval($match[1]);
            }

            $nuevoNumero = $ultimoNumero + 1;
            //$folio = "VENTA-{$nuevoNumero}-{$anioActual}";
            $folio = sprintf("SERVICIO-%05d-%d", $nuevoNumero, $anioActual);

            $reparacion->folio =  $folio;
            $reparacion->cliente_id = $request->cliente_id;
            $reparacion->fecha_ingreso = now();
            $reparacion->equipo = $request->equipo;
            $reparacion->tel1 = $request->tel1;
            $reparacion->tel2 = $request->tel2;
            $reparacion->fallo = $request->fallo;
            $reparacion->nota_adicional = $request->nota_adicional;
            $reparacion->wci = auth()->id();
            $reparacion->save();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Reparación registrada",
                'text' => "La reparación se ha registrado correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                ],
                'buttonsStyling' => false
            ]);


            return redirect()->route('admin.reparacion.index')->with(['id' => $reparacion->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            $query = $e->getMessage();
            session()->flash('swal', [
                'icon' => "error",
                'title' => "Operación fallida",
                'text' => "Hubo un error durante el proceso, por favor intente más tarde." . $query,
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);
            return redirect()->back()
                ->withInput($request->all()) // Aquí solo pasas los valores del formulario
                ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
        }
    }

    public function show(Reparacion $reparacion)
    {
        //
    }

    public function edit($id)
    {
        $metodo = 'edit';
        $reparacion = Reparacion::findOrFail($id);
        return view('reparacion.solucion', compact('reparacion'));
    }

    public function solucion($id)
    {
        $reparacion = Reparacion::findOrFail($id);
        $metodo = 'solucion';


        $detalle = array_values(
            $reparacion->productos->map(function ($item) {
                return [
                    'producto_id'   => $item->producto_id,
                    'name_producto' => $item->producto->nombre ?? 'SIN NOMBRE',
                    'cantidad'      => $item->cantidad,
                    'precio'        => $item->precio_unitario,
                    'total'         => $item->total,
                    'series'        => $item->series,
                    'tipo_item'     => $item->producto->tipo ?? 'PRODUCTO',
                ];
            })->toArray()
        );

        return view('reparacion.solucion', compact('reparacion', 'metodo', 'detalle'));
    }

    public function update(Request $request, Reparacion $reparacion)
    {
        DB::transaction(function () use ($request, $reparacion) {
            // 1️⃣ Validación
            $data = $request->validate([
                'solucion' => 'nullable|string',
                'recomendaciones' => 'nullable|string',
                'nota_general' => 'nullable|string',
                'detalles' => 'required|array|min:1',
                'detalles.*.producto_id' => 'required|exists:productos,id',
                'detalles.*.cantidad' => 'required|integer|min:1',
                'detalles.*.precio' => 'required|numeric|min:0',
                'detalles.*.total' => 'required|numeric|min:0',
                'detalles.*.tipo_item' => 'required|string|in:PRODUCTO,SERVICIO',
            ]);

            // 2️⃣ Actualizar campos de reparación
            $reparacion->update([
                'solucion' => $data['solucion'] ?? null,
                'recomendaciones' => $data['recomendaciones'] ?? null,
                'nota_general' => $data['nota_general'] ?? null,
                'fecha_listo' => now(),
                'estatus' => 'listo',
            ]);

            // 3️⃣ Restaurar inventario de productos previos
            foreach ($reparacion->productos as $detallePrevio) {
                if ($detallePrevio->producto?->tipo === 'PRODUCTO') {
                    $inventario = Inventario::where('producto_id', $detallePrevio->producto_id)
                        ->where('sucursal_id', auth()->user()->sucursal_id) // 👈 ajusta si tu sistema usa multi-sucursal
                        ->first();

                    if ($inventario) {
                        $inventario->decrement('producto_servicio', $detallePrevio->cantidad);
                    }

                    // Registrar en kardex (entrada por edición)
                    Kardex::create([
                        'sucursal_id' => $inventario->sucursal_id ?? null,
                        'producto_id' => $detallePrevio->producto_id,
                        'movimiento_id' => $reparacion->id,
                        'tipo_movimiento' => 'ENTRADA',
                        'tipo_detalle' => 'SERVICIO',
                        'fecha' => now(),
                        'folio' => $reparacion->folio,
                        'descripcion' => 'Devolución por edición de reparación',
                        'debe' => $detallePrevio->cantidad,
                        'haber' => 0,
                        'saldo' => $inventario->producto_servicio ?? 0,
                        'wci'   => auth()->id(),
                    ]);
                }
            }

            // 4️⃣ Eliminar productos anteriores
            $reparacion->productos()->delete();

            // 5️⃣ Insertar los nuevos productos/servicios
            foreach ($data['detalles'] as $detalle) {
                $nuevo = $reparacion->productos()->create([
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio'],
                    'total' => $detalle['total'],
                    'activo' => 1,
                ]);

                if ($detalle['tipo_item'] === 'PRODUCTO') {
                    // 5.1️⃣ Restar stock
                    $inventario = Inventario::where('producto_id', $detalle['producto_id'])
                        ->where('sucursal_id', auth()->user()->sucursal_id)
                        ->first();

                    if ($inventario) {
                        $inventario->increment('producto_servicio', $detalle['cantidad']);
                    }

                    // 5.2️⃣ Registrar salida en kardex
                    Kardex::create([
                        'sucursal_id' => $inventario->sucursal_id ?? null,
                        'producto_id' => $detalle['producto_id'],
                        'movimiento_id' => $reparacion->id,
                        'tipo_movimiento' => 'SALIDA',
                        'tipo_detalle' => 'SERVICIO',
                        'fecha' => now(),
                        'folio' => $reparacion->folio,
                        'descripcion' => 'Salida por reparación',
                        'debe' => 0,
                        'haber' => $detalle['cantidad'],
                        'saldo' => $inventario->producto_servicio ?? 0,
                        'wci'   => auth()->id(),
                    ]);
                }
            }
        });

        return redirect()->back()
            ->with('success', 'Reparación actualizada con éxito.')
            ->with('id', $reparacion->id);
    }

    public function destroy($id)
    {
        $reparacion = Reparacion::findOrFail($id);

        // 🔄 Devolver productos al inventario
        foreach ($reparacion->productos as $detalle) {
            if ($detalle->producto?->tipo === 'PRODUCTO') {
                $inventario = Inventario::where('producto_id', $detalle->producto_id)
                    ->where('sucursal_id', auth()->user()->sucursal_id)
                    ->first();

                if ($inventario) {
                    $inventario->decrement('producto_servicio', $detalle->cantidad);
                }

                Kardex::create([
                    'sucursal_id'    => $inventario->sucursal_id ?? null,
                    'producto_id'    => $detalle->producto_id,
                    'movimiento_id'  => $reparacion->id,
                    'tipo_movimiento' => 'ENTRADA',
                    'tipo_detalle'   => 'SERVICIO',
                    'fecha'          => now(),
                    'folio'          => $reparacion->folio,
                    'descripcion'    => "Devolución de productos por cancelación de reparación {$reparacion->folio}",
                    'debe'           => $detalle->cantidad,
                    'haber'          => 0,
                    'saldo'          => $inventario->producto_servicio ?? 0,
                    'wci'            => auth()->id(),
                ]);
            }
        }

        // 🔒 Marcar reparación como cancelada
        $reparacion->estatus    = 'eliminado';
        $reparacion->finalizada = 0;
        $reparacion->activo     = 0;
        $reparacion->save();

        return redirect()->back()
        ->with('success', 'La reparación fue cancelada y el inventario devuelto.')
        ->with('id', $reparacion->id);
    }

    public function reparador_index_ajax(Request $request)
    {
        // TODAS LAS REPARACIONES PARA EL INDEX
        if ($request->origen == 'reparador.index') {

            $reparadores = User::where(function ($q) {
                $q->where('es_reparador', true)
                    ->orWhere('es_externo', true);
            })
                ->select('id', 'name')
                ->get();

            $reparacion = Reparacion::with(['cliente', 'productos.producto'])
                ->withCount('productos')
                ->orderBy('fecha_ingreso', 'desc')
                ->get()
                ->map(function ($item) use ($reparadores) {
                    $acciones = '';

                    $acciones .= '
                <a href="' . route('ticket.reparacion', $item->id) . '" target="_blank"
                    data-popover-target="ticket-tooltip' . $item->id . '" data-popover-placement="bottom"
                    class="mb-1 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                        <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-6 4h6m-6 4h6M6 3v18l2-2 2 2 2-2 2 2 2-2 2 2V3l-2 2-2-2-2 2-2-2-2 2-2-2Z"/>
                        </svg>
                        <span class="sr-only">Ticket</span>
                </a>
                <div id="ticket-tooltip' . $item->id . '" role="tooltip"
                    class="absolute z-10 invisible inline-block w-38 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                    <div class="p-2 space-y-2">
                        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Ticket</h6>
                    </div>
                </div>';

                    // ✅ Solo mostrar botones adicionales si la reparación NO está activa
                    if ($item->estatus !== 'eliminado') {
                        if ($item->activo != 1) {
                            // ✅ Pagar servicio: solo si tiene reparador externo y costo_servicio > 0
                            if ($item->reparador && $item->reparador->es_externo) {
                                $acciones .= '
                                <a href="#"
                                    data-id="' . $item->id . '"
                                    data-popover-target="tooltip-pagar-' . $item->id . '"
                                    data-popover-placement="left"
                                    class="pagar-servicio text-white mb-1 bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center dark:bg-purple-500 dark:hover:bg-purple-600 dark:focus:ring-purple-700">
                                    <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 18h14M5 18v3h14v-3M5 18l1-9h12l1 9M16 6v3m-4-3v3m-2-6h8v3h-8V3Zm-1 9h.01v.01H9V12Zm3 0h.01v.01H12V12Zm3 0h.01v.01H15V12Zm-6 3h.01v.01H9V15Zm3 0h.01v.01H12V15Zm3 0h.01v.01H15V15Z"/>
                                    </svg>
                                    <span class="sr-only">Pagar servicio</span>
                                    </a>

                                    <div id="tooltip-pagar-' . $item->id . '" role="tooltip"
                                        class="absolute z-10 invisible inline-block w-30 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    <div class="p-2">
                                        <h6 class="font-semibold text-gray-900 dark:text-white">Pagar servicio</h6>
                                    </div>
                                    </div>
                            ';
                            }

                            // ✅ Productos/Servicios: siempre disponible
                            $acciones .= '
                            <a href="' . route('admin.reparacion.solucion', $item->id) . '"
                                data-popover-target="solucion-tooltip' . $item->id . '" data-popover-placement="bottom"
                                class="mb-1 productos-servicios text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                                <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z"/>
                                </svg>
                                <span class="sr-only">Productos/Servicios</span>
                            </a>
                            <div id="solucion-tooltip' . $item->id . '" role="tooltip"
                                class="absolute z-10 invisible inline-block w-38 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                <div class="p-2 space-y-2">
                                    <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Productos/Servicios</h6>
                                </div>
                            </div>
                            ';

                            // ✅ Pasar a venta (solo si tiene productos en reparacion_productos)
                            if ($item->productos_count > 0) {
                                $acciones .= '
                                    <a href="' . route('admin.ventas.create', [
                                    'reparacion_id' => $item->id,
                                    'cliente_id' => $item->cliente_id,
                                ]) . '"
                                    data-popover-target="venta-tooltip' . $item->id . '" data-popover-placement="bottom"
                                    class="mb-1 pasar-venta text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                                        <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z"/>
                                        </svg>
                                        <span class="sr-only">Pasar a venta</span>
                                    </a>
                                    <div id="venta-tooltip' . $item->id . '" role="tooltip"
                                        class="absolute z-10 invisible inline-block w-38 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                        <div class="p-2 space-y-2">
                                            <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Pasar a venta</h6>
                                        </div>
                                    </div>
                                    ';
                            }
                        }
                    }

                    // 🔒 Solo no mostrar si ya está eliminado
                    if ($item->estatus !== 'eliminado' && $item->estatus !== 'entregado') {
                        $acciones .= '
                            <form action="' . route('admin.reparacion.destroy', $item->id) . '" method="POST" class="form-eliminar" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="button"
                                    data-id="' . $item->id . '"
                                    class="btn-eliminar mb-1 text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                                    <svg class="w-5 h-5 text-gray-100 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="sr-only">Eliminar</span>
                                </button>
                            </form>
                            <div id="delete-tooltip' . $item->id . '" role="tooltip"
                                class="absolute z-10 invisible inline-block w-38 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                <div class="p-2 space-y-2">
                                    <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Eliminar</h6>
                                </div>
                            </div>
                        ';
                    }

                    return [
                        'id' => $item->id,
                        'folio' => $item->folio,
                        'cliente_nombre' => $item->cliente?->full_name ?? 'Sin cliente',
                        'tel1' => $item->tel1,
                        'fecha_ingreso' => $item->fecha_ingreso,
                        'fecha_listo' => $item->fecha_listo,
                        'fecha_entregado' => $item->fecha_entregado,
                        'equipo' => $item->equipo,
                        'reparador_id' => $item->reparador_id,
                        'reparador_nombre' => $item->reparador?->name ?? 'Sin asignar',
                        'costo_servicio' => $item->costo_servicio,
                        'venta_id' => $item->venta?->folio ?? 'Sin venta',
                        'estatus_label' => match ($item->estatus) {
                            'listo' => '<span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">Listo</span>',
                            'entregado'    => '<span class="bg-gray-100 text-gray-800 text-sm font-medium px-2.5 py-0.5 rounded">Entregado</span>',
                            'eliminado'    => '<span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded">Eliminado</span>',
                            default       => '<span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-2.5 py-0.5 rounded">Taller</span>',
                        },
                        'acciones' => $acciones,
                        'reparador_select' => view('reparacion.partials._reparador_select', [
                            'reparadores' => $reparadores,
                            'reparacion' => $item
                        ])->render(),
                    ];
                });

            return response()->json(['data' => $reparacion]);
        }
    }

    public function asignarReparador(Request $request)
    {
        // 1️⃣ Validar los datos recibidos
        $request->validate([
            'reparacion_id' => 'required|exists:reparaciones,id',
            'reparador_id'  => 'nullable|exists:users,id', // nullable para poder quitar asignación
        ]);

        // 2️⃣ Obtener la reparación
        $reparacion = Reparacion::findOrFail($request->reparacion_id);

        // 3️⃣ Actualizar el reparador
        $reparacion->reparador_id = $request->reparador_id;
        $reparacion->save();

        // 4️⃣ Devolver respuesta JSON
        return response()->json([
            'message' => 'Reparador asignado correctamente',
            'reparador_nombre' => $reparacion->reparador?->name ?? 'Sin asignar'
        ]);
    }

    public function pagarServicio(Request $request)
    {
        $request->validate([
            'reparacion_id' => 'required|exists:reparaciones,id',
            'monto' => 'required|numeric|min:0',
        ]);

        $reparacion = Reparacion::with('reparador')->findOrFail($request->reparacion_id);

        if (!$reparacion->reparador || !$reparacion->reparador->es_externo) {
            return response()->json(['message' => 'El reparador no es externo.'], 422);
        }

        // ✅ Guardamos el costo ingresado por el usuario (puede ser 0)
        $reparacion->costo_servicio = $request->monto;
        //$reparacion->finalizada = true;
        $reparacion->save();

        // Aquí podrías generar un movimiento en egresos si aplica
        // Egreso::create([...]);

        return response()->json([
            'message' => 'Pago de servicio registrado correctamente.',
            'costo_servicio' => $reparacion->costo_servicio
        ]);
    }

    public function pasarAVentaPost(Request $request)
    {
        $reparacion = Reparacion::with('productos.producto', 'cliente')->findOrFail($request->reparacion_id);

        $detalle = json_decode($request->detalle, true);

        // Guardar en session temporalmente
        //session()->flash('detalle_reparacion', $detalle);
        //session()->flash('reparacion_id', $reparacion->id);
        //session()->flash('cliente_id', $reparacion->cliente_id);

        session()->put('detalle_reparacion', $detalle);
        session()->put('reparacion_id', $reparacion->id);
        session()->put('cliente_id', $reparacion->cliente_id);

        return redirect()->route('admin.ventas.create');
    }

    public function pasarAVenta($id)
    {
        $reparacion = Reparacion::with('productos.producto', 'cliente')->findOrFail($id);

        if ($reparacion->productos->isEmpty()) {
            return redirect()->back()->with('error', 'No hay productos para pasar a venta.');
        }

        // Redirigir con los productos serializados como JSON
        return redirect()->route('admin.ventas.create', [
            'reparacion_id' => $reparacion->id,
            'cliente_id' => $reparacion->cliente_id,
            'detalle' => json_encode($reparacion->productos->map(function ($p) {
                return [
                    'producto_id'   => $p->producto_id,
                    'name_producto' => $p->producto->nombre ?? 'SIN NOMBRE',
                    'cantidad'      => $p->cantidad,
                    'precio'        => $p->precio_unitario,
                    'total'         => $p->total,
                    'tipo_item'     => $p->producto->tipo ?? 'PRODUCTO',
                    'series'        => $p->series ?? '',
                ];
            })),
        ]);
    }

    public function ticket($id)
    {

        $user = auth()->user();
        $userPrinterSize = 80;

        $size = match ($userPrinterSize) {
            58 => [0, 0, 140, 1440],
            80 => [0, 0, 212, 1440],
            default => [0, 0, 0, 0],
        };

        // ✅ Traer reparación con sus relaciones
        $reparacion = Reparacion::with(['cliente', 'reparador', 'productos.producto', 'venta'])
            ->findOrFail($id);

        $pdf = PDF::loadView('comprobantes.ticket_reparacion', compact(
            'userPrinterSize',
            'reparacion'
        ))->setPaper($size, 'portrait');

        return $pdf->stream();
    }
}
