<?php

namespace App\Http\Controllers;

use App\Models\Garantia;
use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

class GarantiaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index(Request $request)
    {
        $now = new \DateTime();
        return view('garantias.index', compact('now'));
    }

    public function create()
    {
        $garantia = new Garantia();
        $garantia->cliente_id = 1; // CLIENTE PÚBLICO por defecto
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

        return view('garantias.create', compact(
            'metodo',
            'garantia',
            'detalle',
            'tipoValues',
            'ejecutivoValues'
        ));
    }

    public function store(Request $request)
    {
        try {

            $garantia = new Garantia();
            // Obtener año actual
            $anioActual = Carbon::now()->year;

            // Buscar el último folio del año actual
            $ultimo = Garantia::whereYear('fecha', $anioActual)
                ->lockForUpdate() // 🔒 bloquea filas de ventas de este año mientras corre la transacción
                ->orderByDesc('id')
                ->value('folio');

            $ultimoNumero = 0;
            if ($ultimo && preg_match('/GARANTIA-(\d+)-' . $anioActual . '/', $ultimo, $match)) {
                $ultimoNumero = intval($match[1]);
            }

            $nuevoNumero = $ultimoNumero + 1;
            //$folio = "VENTA-{$nuevoNumero}-{$anioActual}";
            $folio = sprintf("GARANTIA-%05d-%d", $nuevoNumero, $anioActual);


            $garantia->folio =  $folio;
            $garantia->cliente_id = $request->cliente_id;
            $garantia->tel1 = $request->tel1;
            $garantia->tel2 = $request->tel2;
            $garantia->producto_id = $request->producto_id;
            $garantia->producto_personalizado = $request->producto_personalizado;
            $garantia->cantidad = $request->cantidad;
            $garantia->precio_producto = $request->precio_producto;
            $garantia->importe = $request->importe;

            // Lógica folio de venta
            if ($request->venta_id) {
                $garantia->venta_id = $request->venta_id;
                $garantia->folio_venta_text = null; // No se necesita folio manual
            } else {
                $garantia->venta_id = null;
                $garantia->folio_venta_text = $request->folio_venta; // Guardar folio manual
            }

            $garantia->descripcion_fallo = $request->descripcion_fallo;
            $garantia->informacion_adicional = $request->informacion_adicional;

            $garantia->estatus = $request->estatus ?? 'pendiente';
            $garantia->wci = auth()->id(); // Ejemplo de usuario registrado
            $garantia->save();

             session()->flash('swal', [
                'icon' => "success",
                'title' => "Garantía registrada",
                'text' => "La garantía se ha registrado correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                ],
                'buttonsStyling' => false
            ]);


            return redirect()->route('admin.garantias.index')->with(['id' => $garantia->id]);

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

    public function show(Garantia $garantia)
    {
        //
    }

    public function edit(Garantia $garantia)
    {
        $garantia->load(['cliente', 'producto', 'venta']); // eager loading

        $metodo = 'edit';

        // aquí no necesitas un "detalle" complejo, salvo que quieras armar la fila dinámica
        $detalle = collect([
            [
                'producto_id' => $garantia->producto_id,
                'nombre'      => $garantia->producto?->nombre ?? $garantia->producto_personalizado,
                'cantidad'    => $garantia->cantidad,
                'precio'      => $garantia->precio_producto,
                'importe'     => $garantia->importe,
            ]
        ]);

        return view('garantias.edit', compact(
            'metodo',
            'garantia',
            'detalle'
        ));
    }

    public function update(Request $request, Garantia $garantia)
    {
        try {
            // Si es un formulario de solución
            if ($request->has('solucion')) {
                $garantia->solucion = $request->solucion;
                $garantia->nota_solucion = $request->nota_solucion;
                $garantia->estatus = 'resuelto';
                $garantia->fecha_cierre = now();

                $detalle = [
                    [
                        'producto_id' => $garantia->producto_id,
                        'nombre'      => $garantia->producto?->nombre ?? $garantia->producto_personalizado,
                        'cantidad'    => $garantia->cantidad,
                        'precio'      => $garantia->precio_producto,
                        'importe'     => $garantia->importe,
                    ]
                ];

                switch ($request->solucion) {
                    case 'Nota de crédito':
                        // Generar nota de crédito asociada

                        $notaCredito = $garantia->notaCreditos()->create([
                            'cliente_id' => $garantia->cliente_id,
                            'monto'      => $garantia->importe,
                            'motivo'     => $request->nota_solucion,
                            'tipo'       => 'GARANTIA',
                            'activo'     => true,
                        ]);
                        $sucursalId = auth()->user()->sucursal_id;

                        foreach ($detalle as $item) {
                            $cantidadAgregar = $item['cantidad'];
                            $productoId = $item['producto_id'];

                            // Actualizamos inventario: sumamos a producto_garantia
                            $inventario = Inventario::where('producto_id', $productoId)
                                            ->where('sucursal_id', $sucursalId)
                                            ->first();

                            if ($inventario) {
                                $saldoActual = $inventario->cantidad;
                                $saldoGarantia = $inventario->producto_garantia;

                                $inventario->producto_garantia += $cantidadAgregar;
                                $inventario->save();

                                // Registrar movimiento en Kardex
                                Kardex::create([
                                    'sucursal_id'     => $sucursalId,
                                    'producto_id'     => $productoId,
                                    'movimiento_id'   => $notaCredito->id,
                                    'tipo_movimiento' => 'ENTRADA',      // Entrada a garantía
                                    'tipo_detalle'    => 'GARANTIA',
                                    'fecha'           => now(),
                                    'folio'           => $notaCredito->folio ?? '',
                                    'debe'            => $cantidadAgregar,
                                    'haber'           => 0,
                                    'saldo'           => $saldoActual,               // stock normal no cambia
                                    //'saldo_garantia'  => $saldoGarantia + $cantidadAgregar,
                                    'descripcion'     => 'Nota de crédito - entrada a garantía',
                                    'wci'             => auth()->id(),
                                    'activo'          => 1,
                                ]);
                            }
                        }
                        break;

                    case 'Cambio físico':
                        // Reconstruimos $detalle desde la garantía


                        // Obtenemos la sucursal del usuario autenticado
                        $sucursalId = auth()->user()->sucursal_id;

                        // Supongamos que $detalle es un arreglo con los productos de la garantía
                        foreach ($detalle as $item) {
                            $cantidadDevolver = $item['cantidad']; // Cantidad del producto a devolver/cambiar
                            $productoId = $item['producto_id'];

                            // Actualizamos inventario: solo incrementamos la cantidad del producto en garantía
                            $inventario = Inventario::where('producto_id', $productoId)
                                            ->where('sucursal_id', $sucursalId)
                                            ->first();

                            if ($inventario) {
                                // Guardamos valores actuales
                                $saldoActual = $inventario->cantidad;
                                $saldoGarantia = $inventario->producto_garantia;

                                // ✅ Reducir el stock general (cantidad)
                                $inventario->cantidad -= $cantidadDevolver;

                                // ✅ Sumar a producto_garantia
                                $inventario->producto_garantia += $cantidadDevolver;

                                $inventario->save();

                                // 1️⃣ Registrar SALIDA del inventario normal
                                Kardex::create([
                                    'sucursal_id'     => $sucursalId,
                                    'producto_id'     => $productoId,
                                    'movimiento_id'   => $garantia->id,
                                    'tipo_movimiento' => 'SALIDA',
                                    'tipo_detalle'    => 'GARANTIA',
                                    'fecha'           => now(),
                                    'folio'           => $garantia->folio ?? '',
                                    'debe'            => 0,
                                    'haber'           => $cantidadDevolver,
                                    'saldo'           => $saldoActual - $cantidadDevolver,
                                    //'saldo_garantia'  => $saldoGarantia,
                                    'descripcion'      => 'Cambio físico - salida por defecto',
                                    'wci'             => auth()->id(),
                                    'activo'          => 1,
                                ]);

                                // 2️⃣ Registrar ENTRADA a garantía
                                Kardex::create([
                                    'sucursal_id'     => $sucursalId,
                                    'producto_id'     => $productoId,
                                    'movimiento_id'   => $garantia->id,
                                    'tipo_movimiento' => 'ENTRADA',
                                    'tipo_detalle'    => 'GARANTIA',
                                    'fecha'           => now(),
                                    'folio'           => $garantia->folio ?? '',
                                    'debe'            => $cantidadDevolver,
                                    'haber'           => 0,
                                    'saldo'           => $saldoActual - $cantidadDevolver,
                                    //'saldo_garantia'  => $saldoGarantia + $cantidadDevolver,
                                    'descripcion'      => 'Cambio físico - entrada a garantía',
                                    'wci'             => auth()->id(),
                                    'activo'          => 1,
                                ]);
                            }
                        }
                        break;

                    case 'No procede':
                        // Lógica si no procede
                        break;
                }
            }

            // 2️⃣ Flujo: destino del producto (reasignado o baja)
            if ($request->has('destino_producto')) {
                $garantia->destino_producto = $request->destino_producto;
                $garantia->fecha_destino = now();

                $sucursalId = auth()->user()->sucursal_id;
                $productoId = $garantia->producto_id;
                $cantidad   = $garantia->cantidad;

                $inventario = Inventario::where('producto_id', $productoId)
                                ->where('sucursal_id', $sucursalId)
                                ->first();

                if ($inventario) {
                    switch ($request->destino_producto) {
                        case 'reasignado':
                            // Sacar de garantía y regresar a stock normal
                            $inventario->producto_garantia -= $cantidad;
                            $inventario->cantidad += $cantidad;
                            $inventario->save();

                            Kardex::create([
                                'sucursal_id'     => $sucursalId,
                                'producto_id'     => $productoId,
                                'movimiento_id'   => $garantia->id,
                                'tipo_movimiento' => 'ENTRADA',
                                'tipo_detalle'    => 'GARANTIA',
                                'fecha'           => now(),
                                'folio'           => $garantia->folio ?? '',
                                'debe'            => $cantidad,
                                'haber'           => 0,
                                'saldo'           => $inventario->cantidad,
                                'descripcion'     => 'Reasignación desde garantía',
                                'wci'             => auth()->id(),
                                'activo'          => 1,
                            ]);
                            break;

                        case 'baja':
                            // Sacar de garantía pero no regresa a stock
                            $inventario->producto_garantia -= $cantidad;
                            $inventario->save();

                            Kardex::create([
                                'sucursal_id'     => $sucursalId,
                                'producto_id'     => $productoId,
                                'movimiento_id'   => $garantia->id,
                                'tipo_movimiento' => 'SALIDA',
                                'tipo_detalle'    => 'GARANTIA',
                                'fecha'           => now(),
                                'folio'           => $garantia->folio ?? '',
                                'debe'            => 0,
                                'haber'           => $cantidad,
                                'saldo'           => $inventario->cantidad,
                                'descripcion'     => 'Baja definitiva de producto en garantía',
                                'wci'             => auth()->id(),
                                'activo'          => 1,
                            ]);
                            break;
                    }
                }
            }

            // 3️⃣ Flujo: actualización de datos generales (cuando no es solución ni destino)
            if (!$request->has('solucion') && !$request->has('destino_producto')) {
                // Actualizar campos básicos
                $garantia->cliente_id = $request->cliente_id;
                $garantia->tel1 = $request->tel1;
                $garantia->tel2 = $request->tel2;
                $garantia->producto_id = $request->producto_id;
                $garantia->producto_personalizado = $request->producto_personalizado;
                $garantia->cantidad = $request->cantidad;
                $garantia->precio_producto = $request->precio_producto;
                $garantia->importe = $request->importe;

                // Lógica folio de venta
                if ($request->venta_id) {
                    $garantia->venta_id = $request->venta_id;
                    $garantia->folio_venta_text = null; // Folio manual no se necesita
                } else {
                    $garantia->venta_id = null;
                    $garantia->folio_venta_text = $request->folio_venta; // Folio manual
                }

                $garantia->descripcion_fallo = $request->descripcion_fallo;
                $garantia->informacion_adicional = $request->informacion_adicional;
            }

            $garantia->estatus = $request->estatus ?? $garantia->estatus;
            $garantia->wci = auth()->id(); // Actualiza usuario que modificó

            $garantia->save();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Garantía actualizada",
                'text' => "La garantía se ha actualizado correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.garantias.index')->with(['id' => $garantia->id]);

        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => "error",
                'title' => "Operación fallida",
                'text' => "Hubo un error durante el proceso, por favor intente más tarde. " . $e->getMessage(),
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->back()->withInput($request->all())
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function agregarSolucion(Garantia $garantia)
    {
        $metodo = 'solucion'; // solo para agregar solución
        $garantia->load(['cliente', 'producto', 'venta']); // eager loading

        // aquí no necesitas un "detalle" complejo, salvo que quieras armar la fila dinámica
        $detalle = collect([
            [
                'producto_id' => $garantia->producto_id,
                'nombre'      => $garantia->producto?->nombre ?? $garantia->producto_personalizado,
                'cantidad'    => $garantia->cantidad,
                'precio'      => $garantia->precio_producto,
                'importe'     => $garantia->importe,
            ]
        ]);
        return view('garantias.solucion', compact('garantia', 'metodo','detalle'));
    }

    public function destroy(Garantia $garantia)
    {
        //
    }

    public function garantia_index_ajax(Request $request)
    {
        // TODAS LAS GARANTIAS PARA EL INDEX
        if ($request->origen == 'garantia.index') {

            $filtro = $request->input('filtro')
            ?? $request->input('mes_hidden')
            ?? $request->input('rango');
            $mes          = $request->input('mes');          // '2026-01'
            $fechaInicio  = $request->input('fechaInicio');  // '2026-01-01'
            $fechaFin     = $request->input('fechaFin');     // '2026-01-31'

            $garantiaQuery = Garantia::with(['cliente', 'producto', 'venta'])
            ->orderBy('fecha_registro', 'desc');

            if ($filtro === 'MES' && filled($mes)) {
                $fecha = Carbon::createFromFormat('Y-m', $mes);

                $garantiaQuery
                    ->whereYear('fecha_registro', $fecha->year)
                    ->whereMonth('fecha_registro', $fecha->month);
            }

            if ($filtro === 'RANGO' && $fechaInicio && $fechaFin) {
                $garantiaQuery->whereBetween('fecha_registro', [
                    Carbon::parse($fechaInicio)->startOfDay(),
                    Carbon::parse($fechaFin)->endOfDay(),
                ]);
            }

            if (!$filtro) {
                $garantiaQuery
                    ->whereMonth('fecha_registro', now()->month)
                    ->whereYear('fecha_registro', now()->year);
            }

            $garantias = $garantiaQuery
            ->get()
            ->map(function ($item) {
                $acciones = '';

                // Ticket
                //if ($item->venta) {
                    //$acciones .= '<a href="'.route('ticket.venta', $item->venta->id).'" target="_blank" class="btn btn-green">Ticket</a>';

                    $acciones .= '
                    <a href="' . route('ticket.garantia', $item->id) . '" target="_blank"
                        data-popover-target="ticket-tooltip'.$item->id.'" data-popover-placement="bottom"
                        class="mb-1 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                            <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-6 4h6m-6 4h6M6 3v18l2-2 2 2 2-2 2 2 2-2 2 2V3l-2 2-2-2-2 2-2-2-2 2-2-2Z"/>
                            </svg>
                            <span class="sr-only">Ticket</span>
                    </a>
                    <div id="ticket-tooltip'.$item->id.'" role="tooltip"
                        class="absolute z-10 invisible inline-block w-38 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                        <div class="p-2 space-y-2">
                            <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Ticket</h6>
                        </div>
                    </div>';
                //}

                // Solución (pendiente)
                if ($item->estatus === 'pendiente') {
                    $acciones .= '
                    <a href="'.route('admin.garantias.solucion', $item->id).'"
                        data-popover-target="solucion-tooltip'.$item->id.'" data-popover-placement="bottom"
                        class="mb-1 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                            <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span class="sr-only">Solución</span>
                    </a>
                    <div id="solucion-tooltip'.$item->id.'" role="tooltip"
                        class="absolute z-10 invisible inline-block w-38 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                        <div class="p-2 space-y-2">
                            <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Solución</h6>
                        </div>
                    </div>';

                    $acciones .= '
                    <a href="'.route('admin.garantias.edit', $item->id).'"
                        data-popover-target="edit-tooltip'.$item->id.'" data-popover-placement="bottom"
                        class="mb-1 text-white bg-yellow-400 hover:bg-yellow-500 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                            <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                            </svg>
                            <span class="sr-only">Editar</span>
                    </a>
                    <div id="edit-tooltip'.$item->id.'" role="tooltip"
                        class="absolute z-10 invisible inline-block w-38 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                        <div class="p-2 space-y-2">
                            <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Editar</h6>
                        </div>
                    </div>';

                    $acciones .= '
                    <a href="'.route('admin.garantias.destroy', $item->id).'"
                        data-popover-target="delet-tooltip'.$item->id.'" data-popover-placement="bottom"
                        class="mb-1 text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                            <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span class="sr-only">Eliminar</span>
                    </a>
                    <div id="delet-tooltip'.$item->id.'" role="tooltip"
                        class="absolute z-10 invisible inline-block w-38 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                        <div class="p-2 space-y-2">
                            <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Eliminar</h6>
                        </div>
                    </div>';
                }

                // Acciones solo si está resuelto
                if ($item->estatus === 'resuelto' && $item->destino_producto === null && $item->solucion !== 'No procede') {
                    $acciones .= '
                        <form method="POST" action="'.route('admin.garantias.update', $item->id).'" class="form-destino" style="display:inline;">
                            '.csrf_field().method_field('PUT').'
                            <input type="hidden" name="destino_producto" value="reasignado">
                            <button type="button"
                                data-popover-target="reasignar-tooltip'.$item->id.'" data-popover-placement="bottom"
                                class="btn-destino mb-1 text-white bg-purple-600 hover:bg-purple-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center"
                                data-tipo="reasignado">
                                <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16 10 3-3m0 0-3-3m3 3H5v3m3 4-3 3m0 0 3 3m-3-3h14v-3"/>
                                </svg>
                                <span class="sr-only">Reasignar</span>
                            </button>
                        </form>
                        <div id="reasignar-tooltip'.$item->id.'" role="tooltip"
                            class="absolute z-10 invisible inline-block w-38 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                            <div class="p-2 space-y-2">
                                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Reasignar producto</h6>
                            </div>
                        </div>';

                    $acciones .= '
                        <form method="POST" action="'.route('admin.garantias.update', $item->id).'"  class="form-destino" style="display:inline;">
                            '.csrf_field().method_field('PUT').'
                            <input type="hidden" name="destino_producto" value="baja">
                            <button type="button"
                                data-popover-target="baja-tooltip'.$item->id.'" data-popover-placement="bottom"
                                class="btn-destino mb-1 text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center"
                                data-tipo="baja">
                                <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 20V7m0 13-4-4m4 4 4-4m4-12v13m0-13 4 4m-4-4-4 4"/>
                                </svg>
                                <span class="sr-only">Dar de baja</span>
                            </button>
                        </form>
                        <div id="baja-tooltip'.$item->id.'" role="tooltip"
                            class="absolute z-10 invisible inline-block w-38 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                            <div class="p-2 space-y-2">
                                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Dar de baja</h6>
                            </div>
                        </div>';
                }

                return [
                    'id' => $item->id,
                    'folio' => $item->folio,
                    'cliente_nombre' => $item->cliente?->full_name ?? 'Sin cliente',
                    'tel1' => $item->tel1,
                    'tel2' => $item->tel2,
                    'producto_nombre' => $item->producto?->nombre ?? $item->producto_personalizado ?? 'Producto personalizado',
                    'precio_producto' => number_format($item->precio_producto, 2),
                    'folio_venta' => $item->venta?->folio ?? $item->folio_venta_text ?? 'Sin venta',
                    'descripcion_fallo' => $item->descripcion_fallo,
                    'informacion_adicional' => $item->informacion_adicional,
                    'solucion' => $item->solucion,
                    'estatus' => $item->estatus,
                    'fecha_registro' => $item->fecha_registro,
                    'estatus_label' => match ($item->estatus) {
                        'en_revision' => '<span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">Abierta</span>',
                        'resuelto'    => '<span class="bg-gray-100 text-gray-800 text-sm font-medium px-2.5 py-0.5 rounded">Cerrada</span>',
                        default       => '<span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-2.5 py-0.5 rounded">Pendiente</span>',
                    },
                    'acciones' => $acciones,
                ];
            });

            return response()->json(['data' => $garantias]);
        }

    }

    public function verificarExistenciaCambioFisico(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|integer|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $productoId = $request->producto_id;
        $cantidad = $request->cantidad;

        // Obtenemos el producto junto con el inventario de la sucursal del usuario autenticado
        $producto = Producto::where('activo', 1)
            ->where('tipo', 'PRODUCTO')
            ->where('id', $productoId)
            ->with('inventarioUsuario')
            ->first();

        if (!$producto) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Producto no encontrado o inactivo.',
            ]);
        }

        // Cantidad disponible en inventario de la sucursal
        $inventario = $producto->inventarioUsuario?->cantidad ?? 0;

        if ($inventario >= $cantidad) {
            return response()->json([
                'success' => true,
                'mensaje' => 'Hay suficiente inventario para el cambio físico.',
                'producto' => [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'precio' => $producto->precio,
                    'inventario_disponible' => $inventario,
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'mensaje' => 'No hay suficiente inventario para el cambio físico.',
                'disponible' => $inventario,
            ]);
        }
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

        $garantia = Garantia::with(['cliente', 'producto', 'venta'])->findOrFail($id);

        $pdf = PDF::loadView('comprobantes.ticket_garantia', compact('userPrinterSize','garantia'))
            ->setPaper($size,'portrait');
        return $pdf->stream();
    }
}
