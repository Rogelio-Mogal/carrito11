<?php

namespace App\Http\Controllers;

use App\Models\AsignarGasto;
use App\Models\FormaPago;
use App\Models\Gasto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AsignarGastosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:asignar_gasto.ver')
        ->only(['index', 'show']);

        $this->middleware('permission:asignar_gasto.crear')
            ->only(['create', 'store']);

        $this->middleware('permission:asignar_gasto.editar')
            ->only(['edit', 'update']);

        $this->middleware('permission:asignar_gasto.eliminar')
            ->only(['destroy']);
    }

    public function index()
    {
        /*
        setlocale(LC_ALL, "Spanish" );
        $mes = request('mes');
        $fechaInicio = $request->get('fechaInicio');
        $fechaFin = $request->get('fechaFin');

        if( is_null($mes) ){
            $now = new \DateTime();
            $fechaHoy = Carbon::now()->format('m');
            $asignarGastos = AsignarGasto::with(['gasto.tipoGasto', 'formaPago'])
            ->whereMonth('asignar_gastos.fecha', $fechaHoy)
            ->orderBy('id', 'DESC')
            ->get();
            return view('asignar_gasto.index', compact('now','asignarGastos'));
        }else{
            $digitosMes = substr($mes,-2);
            if($request->get('mes_hidden') == 'MES'){
                $asignarGastos = AsignarGasto::with(['gasto.tipoGasto', 'formaPago'])
                ->whereMonth('asignar_gastos.fecha', $digitosMes)
                ->orderBy('id', 'DESC')
                ->get();
                return view ('asignar_gasto.index', compact( 'asignarGastos','mes'));
            }
            if($request->get('rango') == 'RANGO'){
                $asignarGastos = AsignarGasto::with(['gasto.tipoGasto', 'formaPago'])
                ->whereBetween('asignar_gastos.fecha', ["$fechaInicio 00:00:00","$fechaFin 23:59:59"])
                ->orderBy('id', 'DESC')
                ->get();
                return view ('asignar_gasto.index', compact( 'asignarGastos','mes'));
            }
        }
        */

        $now = new \DateTime();
        return view ('asignar_gasto.index', compact('now'));
    }

    public function create()
    {
        $asignarGasto = new AsignarGasto();
        $metodo = 'create';
        $gasto =  Gasto::with('tipoGasto')
        ->where('activo', 1)
        ->select('id', 'gasto', 'tipo_gasto_id')
        ->get();
        $formaPago = FormaPago::where('activo', 1)
        ->select('id', 'forma_pago')
        ->get();

        return view('asignar_gasto.create', compact('asignarGasto','metodo','gasto','formaPago'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gasto_id.*' => 'required|exists:gastos,id',
            'forma_pago_id.*' => 'required|exists:forma_pagos,id',
            'fecha.*' => 'required|date',
            'monto.*' => 'required|numeric|min:0.01',
            'nota.*' => 'nullable|string|min:2|max:1500',
        ]);

        // Personalizar nombres de atributos
        $customAttributes = [
            'gasto_id.*' => 'gastos',
            'forma_pago_id.*' => 'forma de pago',
            'fecha.*' => 'fecha',
            'monto.*' => 'monto',
            'nota.*' => 'nota',
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

        try{
            foreach ($request->gasto_id as $key => $value) {
                $data = array(
                    'gasto_id' => $request->gasto_id[$key],
                    'forma_pago_id' => $request->forma_pago_id[$key],
                    'fecha' => $request->fecha[$key],
                    'monto' => $request->monto[$key],
                    'nota' => $request->nota[$key],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                );
                AsignarGasto::insert($data);
            }

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La asignación de gasto se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.asignar.gasto.index');
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

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $asignarGasto = AsignarGasto::findorfail($id);

        // ACTIVAMOS EL REGISTRO
        if ($request->activa == 1){
            try {
                // Actualiza los campos necesarios
                $asignarGasto->update([
                    'activo' => 1
                ]);

                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "La asignación de gasto se activo correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'La asignación de gasto se activo correctamente.'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'swal' => [
                        'icon' => "error",
                        'title' => "Operación fallida",
                        'text' => "Hubo un error durante el proceso, por favor intente más tarde.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'error' => $e->getMessage(),
                ], 400);
            }
        }
    }

    public function destroy($id)
    {
        try {
            $asignarGasto = AsignarGasto::findorfail($id);


            $asignarGasto->update([
                'activo' => 0
            ]);

            return response()->json([
                'swal' => [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "La asignación de gasto se eliminó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ],
                'success' => 'La asignación de gasto se eliminó correctamente.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'swal' => [
                    'icon' => "error",
                    'title' => "Operación fallida",
                    'text' => "Hubo un error durante el proceso, por favor intente más tarde.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ],
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function asignar_gasto_index_ajax(Request $request)
    {
        if ($request->origen == 'asignar.gasto.index') {

            if ($request->filtro === "NINGUNO") {
                $query = AsignarGasto::with(['gasto.tipoGasto','formaPago'])
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

                $query = AsignarGasto::with(['gasto.tipoGasto', 'formaPago'])
                    ->whereMonth('asignar_gastos.fecha', $fechaHoy)
                    ->orderBy('id', 'DESC');
            }

            // ---------------------------------------
            // 2. FILTRO POR MES (mes_hidden == 'MES')
            // ---------------------------------------
            elseif ($request->mes_hidden === 'MES') {

                $digitosMes = substr($mes, -2);

                $query = AsignarGasto::with(['gasto.tipoGasto', 'formaPago'])
                    ->whereMonth('asignar_gastos.fecha', $digitosMes)
                    ->orderBy('id', 'DESC');
            }

            // ---------------------------------------
            // 3. FILTRO POR RANGO (rango == 'RANGO')
            // ---------------------------------------
            elseif ($request->rango === 'RANGO') {

                $query = AsignarGasto::with(['gasto.tipoGasto', 'formaPago'])
                    ->whereBetween(
                        'asignar_gastos.fecha',
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
                    'id'            => $item->id,
                    'fecha'         => Carbon::parse($item->fecha)->format('d/m/Y H:i:s'),
                    'gasto'         => '$' . number_format($item->gasto->gasto, 2, '.', ','),
                    'tipo'          => $item->gasto->tipoGasto->tipo_gasto,
                    'monto'         => '$' . number_format($item->monto, 2, '.', ','),
                    'forma_pago'    => $item->formaPago->forma_pago,
                    'nota'          => $item->nota,
                    'es_activo'     => $es_activo,
                    'acciones'      => e(view('asignar_gasto.partials.acciones', compact('item'))->render()),
                ];
            });

            return response()->json([ 'data' => $gasto ]);
        }
    }
}
