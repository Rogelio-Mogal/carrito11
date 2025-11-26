<?php

namespace App\Http\Controllers;

use App\Models\TipoGasto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TipoGastoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        $tipogasto = TipoGasto::all();
        return view('tipo_gasto.index');
    }

    public function create()
    {
        $tipogasto = new TipoGasto();
        $metodo = 'create';

        return view('tipo_gasto.create', compact('tipogasto','metodo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_gasto' => 'required|string|max:255|unique:tipo_gastos',
        ]);
        try{
            $tipogasto = new TipoGasto();
            $tipogasto->tipo_gasto = $request->tipo_gasto;
            $tipogasto->save();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El tipo de gasto se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.tipo.gasto.index');
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
        $tipogasto = TipoGasto::findorfail($id);
        $metodo = 'edit';
        return view('tipo_gasto.edit', compact('tipogasto','metodo'));
    }

    public function update(Request $request, $id)
    {
        $tipogasto = TipoGasto::findorfail($id);
        // ACTUALIZAMOS EL REGISTRO
        if ($request->activa == 0){

            $request->validate([
                'tipo_gasto' => "required|string|max:255|unique:tipo_gastos,tipo_gasto,{$tipogasto->id}",
            ]);

            // Asignar el nuevo valor al modelo
            $tipogasto->tipo_gasto = $request->tipo_gasto;

            if ($tipogasto->isDirty()) {
                try{
                    $tipogasto->tipo_gasto = $request->tipo_gasto;
                    $tipogasto->updated_at = Carbon::now();
                    $tipogasto->save();

                    session()->flash('swal', [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "El tipo de gasto se actualizó correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                        ],
                        'buttonsStyling' => false
                    ]);

                    return redirect()->route('admin.tipo.gasto.index');
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
            } else {
                session()->flash('swal', [
                    'icon' => "info",
                    'title' => "Sin cambios",
                    'text' => "No se realizaron cambios en el tipo de gasto.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ]);

                return redirect()->route('admin.tipo.gasto.index');
            }
        }

        // ACTIVAMOS EL REGISTRO
        if ($request->activa == 1){
            try {
                // Remueve los últimos 5 caracteres de 'forma_pago'
                $tipo_gasto = substr($tipogasto->tipo_gasto, 0, -6);


                // Verifica si 'forma_pago'  son únicos
                $isFormaPagoUnique = !TipoGasto::where('tipo_gasto', $tipo_gasto)
                    ->where('id', '!=', $tipogasto->id)
                    ->where('activo', 1) // Verificar solo entre los registros activos
                    ->exists();

                if (!$isFormaPagoUnique) {
                    // Almacena el mensaje de error en la sesión y redirige de vuelta
                    return response()->json([
                        'swal' => [
                            'icon' => "error",
                            'title' => "Error en la operación",
                            'text' => "El tipo de gasto ya existe. Por favor, elija otro.",
                            'customClass' => [
                                'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                            ],
                            'buttonsStyling' => false
                        ],
                        'error' => "El tipo de gasto ya existe. Por favor, elija otro.",
                    ], 400);
                }

                // Actualiza los campos necesarios
                $tipogasto->update([
                    'tipo_gasto' => $tipo_gasto,
                    'activo' => 1
                ]);

                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "El tipo de gasto se activo correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'El tipo de gasto se activo correctamente.'
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
            $tipogasto = TipoGasto::findorfail($id);

            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $tipogasto->update([
                'tipo_gasto' => $tipogasto->tipo_gasto.'-'.substr(str_shuffle($permitted_chars), 0, 5),
                'activo' => 0
            ]);

            return response()->json([
                'swal' => [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El tipo de gasto se eliminó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ],
                'success' => 'El tipo de gasto se eliminó correctamente.'
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

    public function tipo_gasto_index_ajax(Request $request)
    {
        if ($request->origen == 'tipoGasto.index') {

            $tipoGasto = TipoGasto::all()
                ->map(function ($item) {

                    // --- Etiqueta de Matriz ---
                $es_activo = $item->activo == 1
                    ? '<span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">Activo</span>'
                    : '<span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded">Eliminado</span>';

                return [
                    'id'        => $item->id,
                    'tipo_gasto' => $item->tipo_gasto,
                    'es_activo' => $es_activo,
                    'acciones' => e(view('forma_pago.partials.acciones', compact('item'))->render()),
                ];
            });

            return response()->json([ 'data' => $tipoGasto ]);
        }
    }
}
