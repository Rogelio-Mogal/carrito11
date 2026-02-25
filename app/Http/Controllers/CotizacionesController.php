<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\DocumentoDetalle;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CotizacionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:cotizaciones.ver')
        ->only(['index', 'show']);

        $this->middleware('permission:cotizaciones.crear')
            ->only(['create', 'store']);

        $this->middleware('permission:cotizaciones.editar')
            ->only(['edit', 'update']);

        $this->middleware('permission:cotizaciones.eliminar')
            ->only(['destroy']);
    }

    public function index()
    {

        /*foreach ($cotizaciones as $cotizacion) {
            $cotizacion->usuario_nombre = User::find($cotizacion->wci)->name;
        }*/

        return view('cotizaciones.index');
    }

    public function create(Request $request)
    {
        $cotizacion = new Documento;
        $cotizacion->cliente_id = 1;
        $cotizacion->cliente = 'CLIENTE PÚBLICO';
        $cotizacion->direccion = 'DOMICILIO CONOCIDO';
        $cotizacion->tipo_precio = 'CLIENTE PÚBLICO';
        $metodo = 'create';
        $detalle = 0;
        return view('cotizaciones.create', compact('cotizacion', 'metodo','detalle'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date',
            'cliente' => 'required|string|max:255',
            'cantidad.*' => 'required|numeric|min:0.01',
            'precio.*' => 'required|numeric|min:0.01',
        ]);

        // Personalizar nombres de atributos
        $customAttributes = [
            'cantidad.*' => 'cantidad',
            'precio.*' => 'precio',
        ];
        $validator->setAttributeNames($customAttributes);

        // Check validation and add custom messages
        if ($validator->fails()) {
            $errors = $validator->errors();

            foreach ($errors->getMessages() as $field => $messages) {
                if (preg_match('/(cantidad|precio)\.(\d+)/', $field, $matches)) {
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

            // CREAMOS LA COTIZACION
            $fecha = $request->fecha; // Obtiene la fecha (YYYY-MM-DD)
            // Obtiene la hora actual en formato 'HH:MM:SS'
            $horaActual = Carbon::now()->format('H:i:s');

            $cotizacion = new Documento();
            $cotizacion->cliente_id = $request->cliente_id;
            $cotizacion->tipo = $request->tipo;
            $cotizacion->tipo_precio = $request->tipo_precio;
            if($request->name_personalizado == 1){
                $cotizacion->cliente = $request->cliente;
                $cotizacion->direccion = $request->direccion;
            }
            $cotizacion->fecha = $fecha . ' ' . $horaActual;
            $cotizacion->total = 0;
            $cotizacion->estado = 'CREADO';
            $cotizacion->wci = auth()->user()->id;
            $cotizacion->save();

            // Obtener el ID del registro recién insertado
            $cotizacionId = $cotizacion->id;

            // CREAMOS EL COTIZACION DETALLES
            $totalCompra = 0;
            //foreach ($request->producto_id as $key => $value) {
                $importe = $request->importe;

                $nameComun = '';
                if($request->is_producto_comun == '1'){
                    //PRODUCTO COMÚN
                    $nameComun = $request->product_name;
                }

                $data = array(
                    'documento_id' => $cotizacionId,
                    'producto_id' => $request->producto_id,
                    'producto_comun' => $nameComun,
                    'cantidad' => $request->cantidad,
                    'precio' => $request->precio,
                    'precio_publico' => $request->precio_publico,
                    'precio_medio_mayoreo' => $request->precio_medio_mayoreo,
                    'precio_mayoreo' => $request->precio_mayoreo,
                    'importe' => $request->importe,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                );
                DocumentoDetalle::insert($data);

                // Acumulo el importe en el total de la compra
                $totalCompra += $importe;
           // }

            // Actualizo el total de la compra
            $cotizacion->total = $totalCompra;
            $cotizacion->save();


            DB::commit();
            return json_encode($cotizacion);
            /*session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La cotización se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.cotizacion.index');*/

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'swal' => [
                    'icon' => "error",
                    'title' => "Operación fallida",
                    'text' => "Hubo un error durante el proceso, por favor intente más tarde: ".$e->getMessage(),
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ],
                'error' => $e->getMessage(),
            ], 400);

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

    /*
    public function store(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [


            'fecha' => 'required|date', //'required|string|min:2|max:255|unique:compras',
            'cliente' => 'required|string|max:255',
            //'cliente_id' => 'required|date',
            //'tipo' => 'required|integer|min:1',
            //'direccion' => 'required|string|max:255',
            //'tipo_precio' => 'nullable|string|min:2|max:1500',


            //'producto_id.*' => 'required|numeric|min:0.01',
            'cantidad.*' => 'required|numeric|min:0.01',
            //'is_producto_comun.*' => 'required|numeric|min:0.01',
            //'product_name.*' => 'required|numeric|min:0.01',
            //'precio_publico.*' => 'required|numeric|min:0.01',
            //'precio_medio_mayoreo.*' => 'numeric|nullable',
            //'precio_mayoreo.*' => 'numeric|nullable',
            'precio.*' => 'required|numeric|min:0.01',
            //'importe.*' => 'string|nullable|max:255',
        ]);

        // Personalizar nombres de atributos
        $customAttributes = [
            'cantidad.*' => 'cantidad',
            'precio.*' => 'precio',
        ];
        $validator->setAttributeNames($customAttributes);

        // Check validation and add custom messages
        if ($validator->fails()) {
            $errors = $validator->errors();

            foreach ($errors->getMessages() as $field => $messages) {
                if (preg_match('/(cantidad|precio)\.(\d+)/', $field, $matches)) {
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

            // CREAMOS LA COTIZACION
            $fecha = $request->fecha; // Obtiene la fecha (YYYY-MM-DD)
            // Obtiene la hora actual en formato 'HH:MM:SS'
            $horaActual = Carbon::now()->format('H:i:s');

            $cotizacion = new Documento();
            $cotizacion->cliente_id = $request->cliente_id;
            $cotizacion->tipo = $request->tipo;
            $cotizacion->tipo_precio = $request->tipo_precio;
            if($request->name_personalizado == 1){
                $cotizacion->cliente = $request->cliente;
                $cotizacion->direccion = $request->direccion;
            }
            $cotizacion->fecha = $fecha . ' ' . $horaActual;
            $cotizacion->total = 0;
            $cotizacion->estado = 'CREADO';
            $cotizacion->wci = auth()->user()->id;
            $cotizacion->save();

            // Obtener el ID del registro recién insertado
            $cotizacionId = $cotizacion->id;

            // CREAMOS EL COTIZACION DETALLES
            $totalCompra = 0;
            foreach ($request->producto_id as $key => $value) {
                $importe = $request->importe[$key];

                $nameComun = '';
                if($request->is_producto_comun[$key] == 1){
                    //PRODUCTO COMÚN
                    $nameComun = $request->product_name[$key];
                }

                $data = array(
                    'documento_id' => $cotizacionId,
                    'producto_id' => $request->producto_id[$key],
                    'producto_comun' => $nameComun,
                    'cantidad' => $request->cantidad[$key],
                    'precio' => $request->precio[$key],
                    'precio_publico' => $request->precio_publico[$key],
                    'precio_medio_mayoreo' => $request->precio_medio_mayoreo[$key],
                    'precio_mayoreo' => $request->precio_mayoreo[$key],
                    'importe' => $request->importe[$key],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                );
                DocumentoDetalle::insert($data);

                // Acumulo el importe en el total de la compra
                $totalCompra += $importe;
            }

            // Actualizo el total de la compra
            $cotizacion->total = $totalCompra;
            $cotizacion->save();


            DB::commit();
            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La cotización se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.cotizacion.index');

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
    */

    public function show($id)
    {
        $cotizacion = Documento::with(['detallesDocumentos.productoDocumento','clienteDocumento'])->findorfail($id);
        $metodo = 'edit';
        $detalle = $cotizacion->id;

        return view('cotizaciones.show', compact('cotizacion','detalle'));
    }

    public function edit($id)
    {

        $cotizacion = Documento::with(['detallesDocumentos.productoDocumento','clienteDocumento'])->findorfail($id);
        if($cotizacion->activo == 1 && $cotizacion->estado == 'CREADO' ){
            $metodo = 'edit';
            $detalle = $cotizacion->id;
            return view('cotizaciones.edit', compact('cotizacion','metodo','detalle'));
        }
        return redirect()->route('admin.cotizacion.index');
    }

    public function update(Request $request, $id)
    {
        try {

            $cotizacion = Documento::find($id);
            if($request->origen == 'actualiza.datos.cliente'){
                // Verificar si hay elementos en la colección
                if ($cotizacion) {
                    $cliente = $request->name_personalizado == 0 ? '' : ($request->name_personalizado == 1 ? $request->cliente : '');
                    $direccion = $request->name_personalizado == 0 ? '' : ($request->name_personalizado == 1 ? $request->direccion : '');

                    $fecha = $request->fecha; // Obtiene la fecha (YYYY-MM-DD)
                    $horaActual = Carbon::now()->format('H:i:s'); // Obtiene la hora actual en formato 'HH:MM:SS'

                    $cotizacion->update([
                        'fecha'  =>  $fecha . ' ' . $horaActual,
                        'cliente_id' => $request->cliente_id,
                        'cliente' => $cliente,
                        'direccion' => $direccion,
                        'tipo_precio' => $request->tipo_precio,
                    ]);
                }
                return json_encode($cotizacion);
            }
            if($request->origen == 'actualiza.estado'){
                if ($cotizacion) {
                    $cotizacion->update([
                        'estado'  =>  'LISTO',
                    ]);

                    return response()->json([
                        'swal' => [
                            'icon' => "success",
                            'title' => "Operación correcta",
                            'text' => "La cotización esta lista.",
                            'customClass' => [
                                'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                            ],
                            'buttonsStyling' => false
                        ],
                        'success' => 'La cotización esta lista.'
                    ], 200);
                }
            }


        } catch (\Exception $e) {
            $query = $e->getMessage();
            return json_encode($query);

            return \Redirect::back()
                ->with(['receta' => 'fail-destroy', 'error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // BUSCAMOS LA COTIZACION
            $cotizacion = Documento::findorfail($id);

            if($request->origen == "elimina.un.registro"){
                $cotizacion->update(['activo' => 0]);

                // Obtener los detalles de la cotizacion
                DocumentoDetalle::where('documento_id', $id)->update(['activo' => 0]);

                DB::commit();

                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "La cotización se eliminó correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'La cotización se eliminó correctamente.'
                ], 200);
            }else if($request->origen == "elimina.multiple.registros"){
                $idsArray = array_map('intval', explode(',', $id));

                foreach ($idsArray as $id) {
                    Documento::where('id', $id)->update([
                        'activo' => 0,
                    ]);
                }

                \DB::commit();
                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "Las cotizaciones se eliminaron correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'Las cotizaciones se eliminaron correctamente.'
                ], 200);
            }

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

    public function cotizaciones_index_ajax(Request $request)
    {
        if ($request->origen == 'cotizacion.index') {

            $cotizaciones = Documento::where('tipo','COTIZACIÓN')
            ->with(['clienteDocumento'])
            ->where('activo',1)
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($item) {
                // ✅ CHECKBOX
                $check = '';

                if ($item->activo == 1) {
                    $check = '
                        <input type="checkbox"
                            class="checkbox_check w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded"
                            value="' . $item->id . '">
                    ';
                }

                // ✅ CLIENTE
                $cliente = $item->cliente
                    ? $item->cliente
                    : optional($item->clienteDocumento)->full_name;

                // ✅ BADGE ESTADO
                if ($item->estado == 'CREADO') {
                    $estado = '<span class="bg-red-100 text-red-800 text-sm px-2.5 py-0.5 rounded">CREADO</span>';
                } else {
                    $estado = '<span class="bg-green-100 text-green-800 text-sm px-2.5 py-0.5 rounded">LISTO</span>';
                }

                return [
                    'check'      => $check,
                    'id'         => $item->id,
                    'cliente'    => $cliente,
                    'fecha'      => Carbon::parse($item->fecha)->format('d/m/Y H:i:s'),
                    //'folio'      => $item->folio,
                    //'tipo_venta' => $item->tipo_venta,
                    'total'      => '$' . number_format($item->total, 2),
                    'estado'     => $estado,

                    // vista parcial Blade renderizada
                    'acciones' => e(view('cotizaciones.partials.acciones', compact('item'))->render()),
                ];
            });

            return response()->json([
                'data' => $cotizaciones
            ]);
        }
    }
}
