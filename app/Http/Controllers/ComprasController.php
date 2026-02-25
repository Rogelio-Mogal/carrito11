<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\ComprasDetalle;
use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\Precio;
use App\Models\ProductoNumeroSerie;
use App\Models\Producto;
use App\Models\ProductoCodigoBarra;
use App\Models\Proveedor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ComprasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:compras.ver')
        ->only(['index', 'show']);

        $this->middleware('permission:compras.crear')
            ->only(['create', 'store']);

        $this->middleware('permission:compras.editar')
            ->only(['edit', 'update']);

        $this->middleware('permission:compras.eliminar')
            ->only(['destroy']);
    }

    public function index(Request $request)
    {
        //$compras = Compra::where('id', '>', 1)
        //    ->get();

        //foreach ($compras as $compra) {
        //    $compra->usuario_nombre = User::find($compra->wci)->name;
        //}

        $now = new \DateTime();
        return view('compras.index', compact('now'));

        /*
        setlocale(LC_ALL, "Spanish" );
        $mes = request('mes');
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');

        if( is_null($mes) ){
            $now = new \DateTime();
            $fechaHoy = Carbon::now()->format('m');
            $compras = Compra::where('id', '>', 1)
            ->whereMonth('fecha_compra', $fechaHoy)
            ->orderBy('id', 'DESC')
            ->get();
            foreach ($compras as $compra) {
                $compra->usuario_nombre = User::find($compra->wci)->name;
            }

            return view('compras.index', compact('now','compras'));
        }else{
            $digitosMes = substr($mes,-2);
            if($request->get('mes_hidden') == 'MES'){
                $compras = Compra::where('id', '>', 1)
                ->whereMonth('fecha_compra', $digitosMes)
                ->orderBy('id', 'DESC')
                ->get();

                foreach ($compras as $compra) {
                    $compra->usuario_nombre = User::find($compra->wci)->name;
                }

                return view ('compras.index', compact( 'compras','mes'));
            }
            if($request->get('rango') == 'RANGO'){
                $compras = Compra::where('id', '>', 1)
                ->whereBetween('fecha_compra', ["$fechaInicio 00:00:00","$fechaFin 23:59:59"])
                ->orderBy('id', 'DESC')
                ->get();

                foreach ($compras as $compra) {
                    $compra->usuario_nombre = User::find($compra->wci)->name;
                }

                return view ('compras.index', compact( 'compras','mes'));
            }
        }
        */
    }

    public function create(Request $request)
    {
        $compra = new Compra;
        $metodo = 'create';
        $tipoCompra = $request->input('compra');

        $proveedores = Proveedor::where('id', '!=', 1)->get();

        return view('compras.create', compact('compra', 'metodo', 'tipoCompra', 'proveedores'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'num_factura' => 'required|string|min:2|max:255|unique:compras',
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_compra' => 'required|date',
            'cantVenta.*' => 'required|integer|min:1',
            'producto.*' => 'required|string|max:255',
            'serie.*' => 'nullable|string|min:2|max:1500',
            'pu.*' => 'required|numeric|min:0.01',
            'pc.*' => 'required|numeric|min:0.01',
            'pm.*' => 'required|numeric|min:0.01',
            'pmm.*' => 'required|numeric|min:0.01',
            'pp.*' => 'required|numeric|min:0.01',
            'is_serie.*' => 'numeric|nullable',
            'check_serie.*' => 'numeric|nullable',
            'codigo_proveedor.*' => 'string|nullable|max:255',
        ]);

        // Personalizar nombres de atributos
        $customAttributes = [
            'cantVenta.*' => 'cantidad de venta',
            'producto.*' => 'producto',
            'serie.*' => 'serie',
            'pu.*' => 'precio unitario',
            'pc.*' => 'precio costo',
            'pm.*' => 'precio mayoreo',
            'pmm.*' => 'precio medio mayoreo',
            'pp.*' => 'precio público',
        ];
        $validator->setAttributeNames($customAttributes);

        // Check validation and add custom messages
        if ($validator->fails()) {
            $errors = $validator->errors();

            foreach ($errors->getMessages() as $field => $messages) {
                if (preg_match('/(cantVenta|producto|serie|pu|pc|pm|pmm|pp)\.(\d+)/', $field, $matches)) {
                    $item = $matches[2] + 1;
                    $originalFieldName = $matches[1];
                    $fieldName = $customAttributes["{$originalFieldName}.*"] ?? ucfirst($originalFieldName);

                    // Eliminar los mensajes originales
                    $errors->forget($field);

                    foreach ($messages as $message) {
                        $errors->add($field, "El campo {$fieldName} en el item {$item} tiene un error: {$message}");
                    }
                }
            }
            return redirect()->back()->withErrors($errors)->withInput();
        }

        try {
            DB::beginTransaction();

            // CREAMOS LA COMPRA
            $fecha = $request->fecha_compra; // Obtiene la fecha (YYYY-MM-DD)
            // Obtiene la hora actual en formato 'HH:MM:SS'
            $horaActual = Carbon::now()->format('H:i:s');

            $compra = new Compra();
            $compra->sucursal_id = auth()->user()->sucursal_id;
            $compra->proveedor_id = $request->proveedor_id;
            $compra->tipo = $request->tipo;
            $compra->num_factura = $request->num_factura;
            $compra->fecha_compra = $fecha . ' ' . $horaActual; //$request->fecha_compra;
            $compra->fecha_captura = Carbon::now();
            $compra->total = 0;
            $compra->wci = auth()->user()->id;
            $compra->save();

            // Obtener el ID del registro recién insertado
            $compraId = $compra->id;

            // CREAMOS EL COMPRA DETALLES
            $totalCompra = 0;
            foreach ($request->idproducto as $key => $value) {
                $importe = $request->ImporteSalida[$key];
                $data = array(
                    'compra_id' => $compraId,
                    'producto_id' => $request->idproducto[$key],
                    'cantidad' => $request->cantVenta[$key],
                    'precio' => $request->pu[$key],
                    'importe' => $request->ImporteSalida[$key],
                    'wci' => auth()->user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                );
                ComprasDetalle::insert($data);

                // Acumulo el importe en el total de la compra
                $totalCompra += $importe;
            }

            // Actualizo el total de la compra
            $compra->total = $totalCompra;
            $compra->save();


            // CREAMOS O ACTUALIZAMOS EL INVENTARIO
            foreach ($request->idproducto as $key => $value) {
                $producto_id = $request->idproducto[$key];
                $cantidad = $request->cantVenta[$key];
                $precio_costo = $request->pc[$key];
                $precio_publico = $request->pp[$key];
                $precio_medio_mayoreo = $request->pmm[$key];
                $precio_mayoreo = $request->pm[$key];

                // Verificar si el producto ya existe en el inventario
                //$inventario = Inventario::where('producto_id', $producto_id)->first();

                // Buscar inventario por producto y sucursal
                $inventario = Inventario::where('producto_id', $producto_id)
                    ->where('sucursal_id', $compra->sucursal_id)
                    ->first();

                if ($inventario) {
                    $precio_anterior = $inventario->precio_costo;

                    // Actualizar el registro existente
                    $inventario->cantidad = $inventario->cantidad + $cantidad; // Sumar cantidad si ya existe
                    $inventario->precio_costo = $precio_costo;
                    $inventario->precio_anterior = $precio_anterior;
                    $inventario->precio_publico = $precio_publico;
                    $inventario->precio_medio_mayoreo = $precio_medio_mayoreo;
                    $inventario->precio_mayoreo = $precio_mayoreo;
                    $inventario->updated_at = Carbon::now();
                    $inventario->save();
                } else {
                    // Crear un nuevo registro
                    $inventario = new Inventario();
                    $inventario->sucursal_id = auth()->user()->sucursal_id;
                    $inventario->producto_id = $producto_id;
                    $inventario->cantidad = $cantidad;
                    $inventario->precio_costo = $precio_costo;
                    $inventario->precio_anterior = $precio_costo;
                    $inventario->precio_publico = $precio_publico;
                    $inventario->precio_medio_mayoreo = $precio_medio_mayoreo;
                    $inventario->precio_mayoreo = $precio_mayoreo;
                    $inventario->created_at = Carbon::now();
                    $inventario->updated_at = Carbon::now();
                    $inventario->save();
                }
            }

            // CREAMOS EL NUMERO DE SERIE
            foreach ($request->idproducto as $key => $productoId) {
                $series = array_filter(explode('|', trim($request->serie[$key])), fn($s) => !empty(trim($s)));
                foreach ($series as $numero_serie) {
                    // Validar duplicado
                    if (!ProductoNumeroSerie::where('numero_serie', $numero_serie)->exists()) {
                        ProductoNumeroSerie::create([
                            'producto_id' => $productoId,
                            'proveedor_id' => $compra->proveedor_id,
                            'compra_id' => $compraId,
                            'numero_serie' => $numero_serie,
                            'disponible' => 1,
                        ]);
                    }
                }
            }

            // CREAMOS EL CODIGO DEL PROVEEDOR
            foreach ($request->idproducto as $key => $value) {
                //Este método crea un registro si no existe, pero si ya existe,
                // simplemente lo devuelve sin hacer cambios
                if($request->codigo_proveedor[$key] != null){
                    ProductoCodigoBarra::firstOrCreate(
                        [
                            'producto_id' => $request->idproducto[$key],
                            'proveedor_id' => $compra->proveedor_id,
                            'codigo_barra' => $request->codigo_proveedor[$key],
                        ],
                        [
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]
                    );
                }
            }

            // CREAMOS EL KARDEX
            foreach ($request->idproducto as $key => $productoId) {
                // Obtener el último registro del kardex para el producto
                //$ultimoRegistro = Kardex::where('producto_id', $productoId)
                //    ->orderBy('created_at', 'desc')
                //    ->first();

                // Buscar último registro en kardex por producto y sucursal
                $ultimoRegistro = Kardex::where('producto_id', $productoId)
                    ->where('sucursal_id', $compra->sucursal_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                // Inicializar variables para las cantidades
                $saldoActual = $ultimoRegistro ? $ultimoRegistro->saldo : 0;

                // Cantidades a agregar
                $cantidadEntrada = $request->cantVenta[$key] ?? 0;
                $cantidadSalida = 0;

                // Calcular el nuevo saldo
                $nuevoSaldo = $saldoActual + $cantidadEntrada - $cantidadSalida;

                // Crear el nuevo registro en el kardex
                $kardex = new Kardex();
                $kardex->sucursal_id = $compra->sucursal_id;
                $kardex->producto_id = $productoId;
                $kardex->movimiento_id = $compraId; // Asume que ya tienes el ID de la compra
                $kardex->tipo_movimiento = 'ENTRADA'; // O 'SALIDA' según corresponda
                $kardex->tipo_detalle = 'COMPRA';
                $kardex->fecha = Carbon::now();
                $kardex->folio = $compra->num_factura; // Asume que ya tienes el número de factura
                $kardex->debe = $cantidadEntrada;
                $kardex->haber = $cantidadSalida;
                $kardex->saldo = $nuevoSaldo;
                $kardex->wci = auth()->user()->id;
                $kardex->save();
            }

            DB::commit();
            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La compra se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.compras.index');

        } catch (\Exception $e) {
            DB::rollback();
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

    public function show($id)
    {
        $compra = Compra::where('id', '!=', 1)
        ->where('id', $id)
        //->where('activo', 1)
        ->with('compraDetalles.producto')
        ->get();

        return view('compras.show', compact('compra'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // BUSCAMOS LA COMPRA
            $compra = Compra::findorfail($id);
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            // Obtener los detalles de la compra
            $detalles = ComprasDetalle::where('compra_id', $id)->get();

            // Simular la resta de inventario
            foreach ($detalles as $detalle) {
                $inventario = Inventario::where('producto_id', $detalle->producto_id)->first();

                // Simular la nueva cantidad si se cancelara la compra
                $nuevaCantidad = $inventario->cantidad - $detalle->cantidad;

                // Validar si la nueva cantidad sería negativa
                if ($nuevaCantidad < 0) {
                    $productoNombre = $inventario->producto->nombre; // Obtener el nombre del producto
                    return response()->json([
                        'swal' => [
                            'icon' => "error",
                            'title' => "Operación fallida",
                            'text' => "No se puede cancelar la compra. El producto: " . $productoNombre. " ha tenido cambio de existencia.",
                            'customClass' => [
                                'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                            ],
                            'buttonsStyling' => false
                        ],
                        'error' => 'No se puede cancelar la compra. El inventario del producto ' . $productoNombre . ' resultaría en valores negativos.'
                    ], 400);
                }
            }

            // Si todas las validaciones pasaron, se puede proceder con la cancelación

            // Revertir el inventario
            foreach ($detalles as $detalle) {
                $inventario = Inventario::where('producto_id', $detalle->producto_id)->first();
                $producto = Producto::where('id', $detalle->producto_id)->first();

                //OBTENGO LOS PRECIOS
                $precio_anterior = $inventario->precio_anterior;
                $precioPublico = 0;
                $precioMedio = 0;
                $precioMayoreo = 0;
                $entero = ceil($precio_anterior);

                if ($producto->sub_familia > 3) {
                    // El valor de $id es mayor que 0 y no es null ni vacío
                    $tipo = 'especifico';
                    $precios = Precio::where('desde', '<=', $precio_anterior)
                        ->where('hasta', '>=', $precio_anterior)
                        ->where('precio', 'INTERNO')
                        ->where('producto_caracteristica_id', $id)
                        ->first();
                }else{
                    $tipo = 'general';
                    $precios = Precio::where('desde', '<=', $precio_anterior)
                        ->where('hasta', '>=', $precio_anterior)
                        ->where('precio', 'INTERNO')
                        ->where('producto_caracteristica_id', 3)
                        ->first();
                }

                $porcentaje_publico = ($precios->porcentaje_publico / 100 + 1);
                $porcentaje_medio = ($precios->porcentaje_medio / 100 + 1);
                $porcentaje_mayoreo = ($precios->porcentaje_mayoreo / 100 + 1);
                $especifico_publico = $precios->especifico_publico;
                $especifico_medio = $precios->especifico_medio;
                $especifico_mayoreo = $precios->especifico_mayoreo;

                if ($tipo == 'general') {
                    $precioPublico = $entero * $porcentaje_publico;
                    $precioMedio = $entero * $porcentaje_medio;
                    $precioMayoreo = $entero * $porcentaje_mayoreo;

                } else if ($tipo == 'especifico') {
                    $precioPublico = $entero + $especifico_publico;
                    $precioMedio = $entero + $especifico_medio;
                    $precioMayoreo = $entero + $especifico_mayoreo;
                }

                if ($inventario) {
                    $inventario->cantidad -= $detalle->cantidad; // Restar la cantidad del inventario
                    $inventario->precio_costo = $precio_anterior;
                    $inventario->precio_publico = ceil($precioPublico);
                    $inventario->precio_medio_mayoreo = ceil($precioMedio);
                    $inventario->precio_mayoreo = ceil($precioMayoreo);
                    $inventario->updated_at = Carbon::now();
                    $inventario->save();
                }
            }

            // Actualizar los campos `activo` a 0
            $compra->activo = 0;
            $compra->save();

            ComprasDetalle::where('compra_id', $compra->id)->update(['activo' => 0]);

            // Actualizar el número de serie
            $productosSeries = ProductoNumeroSerie::where('compra_id', $compra->id)->get();
            foreach ($productosSeries as $serie) {
                $serie->numero_serie = $serie->numero_serie . '-CANCEL';
                $serie->activo = 0;
                $serie->save();
            }

            // Registrar en el Kardex la cancelación
            foreach ($detalles as $detalle) {
                $ultimoRegistro = Kardex::where('producto_id', $detalle->producto_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $saldoActual = $ultimoRegistro ? $ultimoRegistro->saldo : 0;
                $cantidadSalida = $detalle->cantidad;

                $nuevoSaldo = $saldoActual - $cantidadSalida;

                // Crear un nuevo registro de salida en el kardex
                $kardex = new Kardex();
                $kardex->producto_id = $detalle->producto_id;
                $kardex->movimiento_id = $compra->id;
                $kardex->tipo_movimiento = 'SALIDA';
                $kardex->tipo_detalle = 'CANCELACION';
                $kardex->fecha = Carbon::now();
                $kardex->folio = $compra->num_factura;
                $kardex->debe = 0;
                $kardex->haber = $cantidadSalida;
                $kardex->saldo = $nuevoSaldo;
                $kardex->descripcion = 'VENTA CANCELADA';
                $kardex->wci = auth()->user()->id;
                $kardex->save();
            }

            DB::commit();

            return response()->json([
                'swal' => [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "La compra se eliminó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ],
                'success' => 'La compra se eliminó correctamente.'
            ], 200);



        /*
            $compra->update([
                'num_factura' => $compra->num_factura.'-'.substr(str_shuffle($permitted_chars), 0, 5),
                'activo' => 0,
                'updated_at' => Carbon::now()
            ]);

            // BUSCAMOS COMPRA DETALLES
            $compraDetalle = ComprasDetalle::where('compra_id', $compra->id)->get();
            foreach ($compraDetalle as $row) {
                $row->update([
                    'activo' => 0,
                    'updated_at' => Carbon::now()
                ]);
            }

            // ACTUALIZAMOS EL INVENTARIO
            foreach ($compraDetalle as $row) {
                $producto_id = $row->producto_id;
                $cantidad = $row->cantidad;
                $precio_costo = $row->pc;
                $precio_publico = $row->pp;
                $precio_medio_mayoreo = $row->pmm;
                $precio_mayoreo = $row->pm;

                // Verificar si el producto ya existe en el inventario
                $inventario = Inventario::where('producto_id', $producto_id)->first();

                if ($inventario) {
                    // Actualizar el registro existente
                    $inventario->cantidad = $inventario->cantidad + $cantidad; // Sumar cantidad si ya existe
                    $inventario->precio_costo = $precio_costo;
                    $inventario->precio_anterior = $inventario->precio_costo;
                    $inventario->precio_publico = $precio_publico;
                    $inventario->precio_medio_mayoreo = $precio_medio_mayoreo;
                    $inventario->precio_mayoreo = $precio_mayoreo;
                    $inventario->updated_at = Carbon::now();
                    $inventario->save();
                }
            }

            // ACTUALIZAMOS EL NUMERO DE SERIE
            foreach ($request->idproducto as $key => $value) {
                // Dividir la cadena de números de serie en un array usando el delimitador '|'
                $series = explode('|', trim($request->serie[$key], '|'));
                // Iterar sobre cada número de serie
                foreach ($series as $numero_serie) {
                    if (!empty($numero_serie)) { // Verificar que el número de serie no esté vacío
                        $serie = new ProductoNumeroSerie();
                        $serie->producto_id = $request->idproducto[$key];
                        $serie->proveedor_id = $compra->proveedor_id;;
                        $serie->compra_id = $compraId;
                        $serie->numero_serie = $numero_serie;
                        $serie->created_at = Carbon::now();
                        $serie->updated_at = Carbon::now();
                        $serie->save();
                    }
                }
            }

            // CREAMOS EL KARDEX
            foreach ($request->idproducto as $key => $productoId) {
                // Obtener el último registro del kardex para el producto
                $ultimoRegistro = Kardex::where('producto_id', $productoId)
                    ->orderBy('created_at', 'desc')
                    ->first();

                // Inicializar variables para las cantidades
                $saldoActual = $ultimoRegistro ? $ultimoRegistro->saldo : 0;

                // Cantidades a agregar
                $cantidadEntrada = $request->cantVenta[$key] ?? 0;
                $cantidadSalida = 0;

                // Calcular el nuevo saldo
                $nuevoSaldo = $saldoActual + $cantidadEntrada - $cantidadSalida;

                // Crear el nuevo registro en el kardex
                $kardex = new Kardex();
                $kardex->producto_id = $productoId;
                $kardex->movimiento_id = $compraId; // Asume que ya tienes el ID de la compra
                $kardex->tipo_movimiento = 'ENTRADA'; // O 'SALIDA' según corresponda
                $kardex->tipo_detalle = 'COMPRA';
                $kardex->fecha = Carbon::now();
                $kardex->folio = $compra->num_factura; // Asume que ya tienes el número de factura
                $kardex->debe = $cantidadEntrada;
                $kardex->haber = $cantidadSalida;
                $kardex->saldo = $nuevoSaldo;
                $kardex->wci = auth()->user()->id;
                $kardex->save();
            }
        */
        } catch (\Exception $e) {
            DB::rollback();
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
                ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
        }
    }

    public function busca_factura_duplicada($id)
    {
        $exists = Compra::where('num_factura', $id)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function numero_serie_duplicado($id)
    {
        $exists = ProductoNumeroSerie::where('numero_serie', $id)->exists();

        return response()->json(['exists' => $exists]);
    }

    /*
    public function productos_compra(Request $request)
    {
        // $request->input('existencia')
        // $request->input('inventario')
        if($request->input('inventario') == 0){ //TODOS
            $productos = Productos::where('id', '!=', 1)
                ->where('tipo', '=', 'PRODUCTO')
                ->where('activo', 1)
                ->with(['inventario'])
                ->get();
            //return json_encode($productos);
            return response()->json(['data' => $productos]);
        }
        if($request->input('inventario') == 1){ // CON INVENTARIO
            $productos = Productos::where('id', '!=', 1)
                ->where('activo', 1)
                ->get();
            //return json_encode($productos);
            return response()->json(['data' => $productos]);
        }
    }
    */
    public function compras_index_ajax(Request $request)
    {
        if ($request->origen == 'compras.index') {

            if ($request->filtro === "NINGUNO") {
                $query =  Compra::where('id', '>', 1)
                    ->orderBy('id', 'DESC');
            }

            setlocale(LC_ALL, "Spanish" );

            $mes = $request->mes;
            $fechaInicio = $request->fechaInicio;
            $fechaFin = $request->fechaFin;

            // ---------------------------------------
            // 1. SI NO SE ENVÍA NADA → CARGA POR MES ACTUAL
            // ---------------------------------------
            if (is_null($mes) && is_null($fechaInicio) && is_null($fechaFin)) {

                $fechaHoy = Carbon::now()->format('m');

                $query =  Compra::where('id', '>', 1)
                    ->whereMonth('fecha_compra', $fechaHoy)
                    ->orderBy('id', 'DESC');
            }

            // ---------------------------------------
            // 2. FILTRO POR MES (mes_hidden == 'MES')
            // ---------------------------------------
            elseif ($request->mes_hidden === 'MES') {

                $digitosMes = substr($mes, -2);

                $query =  Compra::where('id', '>', 1)
                    ->whereMonth('fecha_compra', $digitosMes)
                    ->orderBy('id', 'DESC');
            }

            // ---------------------------------------
            // 3. FILTRO POR RANGO (rango == 'RANGO')
            // ---------------------------------------
            elseif ($request->rango === 'RANGO') {

                $query =  Compra::where('id', '>', 1)
                    ->whereBetween(
                        'fecha_compra',
                        ["$fechaInicio 00:00:00", "$fechaFin 23:59:59"]
                    )
                    ->orderBy('id', 'DESC');
            }

            // Ejecutar consulta
            $items = $query->get();

            // ---------------------------------------
            // Mapear para DataTable
            //—---------------------------------------
            $gasto = $items->map(function ($item) {

                $es_activo = $item->activo == 1
                    ? '<span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">Activo</span>'
                    : '<span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded">Eliminado</span>';

                return [
                    'id'                => $item->id,
                    'num_factura'       => $item->num_factura,
                    'usuario_nombre'    => $item->usuario_nombre,
                    'proveedor'         => $item->proveedor->proveedor,
                    'fecha_captura'     => Carbon::parse($item->fecha_captura)->format('d/m/Y H:i:s'),
                    'fecha_compra'      => Carbon::parse($item->fecha_compra)->format('d/m/Y H:i:s'),
                    'total'             => '$' . number_format($item->total, 2, '.', ','),
                    'es_activo'         => $es_activo,
                    'acciones'          => e(view('compras.partials.acciones', compact('item'))->render()),
                ];
            });

            return response()->json([ 'data' => $gasto ]);
        }
    }
}
