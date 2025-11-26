<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\TipoGasto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GastosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        $tipogasto = TipoGasto::where('activo', 1)
        ->select('id', 'tipo_gasto')
        ->get();

        return view('gasto.index', compact('tipogasto'));
    }

    public function create()
    {
        $gasto = new Gasto();
        $metodo = 'create';
        $tipogasto = TipoGasto::where('activo', 1)
        ->select('id', 'tipo_gasto')
        ->get();

        return view('gasto.create', compact('gasto','metodo','tipogasto'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gasto' => 'required|string|max:255|unique:gastos',
            'tipo_gasto_id' => 'required|integer|min:1',
        ]);
        try{
            $gasto = new Gasto();
            $gasto->gasto = $request->gasto;
            $gasto->tipo_gasto_id = $request->tipo_gasto_id;
            $gasto->save();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El gasto se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.gastos.index');
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
        $gasto = Gasto::findorfail($id);
        $metodo = 'edit';
        return view('gasto.edit', compact('gasto','metodo'));
    }

    public function update(Request $request, $id)
    {
        $gasto = Gasto::findorfail($id);
        // ACTUALIZAMOS EL REGISTRO
        if ($request->activa == 0){

            $request->validate([
                'gasto' => "required|string|max:255|unique:gastos,gasto,{$gasto->id}",
                'tipo_gasto_id' => 'required|integer|min:1',
            ]);

            // Asignar el nuevo valor al modelo
            $gasto->gasto = $request->gasto;
            $gasto->tipo_gasto_id = $request->tipo_gasto_id;

            if ($gasto->isDirty()) {
                try{
                    $gasto->gasto = $request->gasto;
                    $gasto->tipo_gasto_id = $request->tipo_gasto_id;
                    $gasto->updated_at = Carbon::now();
                    $gasto->save();

                    session()->flash('swal', [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "El gasto se actualizó correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                        ],
                        'buttonsStyling' => false
                    ]);

                    return redirect()->route('admin.gastos.index');
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
                    'text' => "No se realizaron cambios en el gasto.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ]);

                return redirect()->route('admin.gastos.index');
            }
        }

        // ACTIVAMOS EL REGISTRO
        if ($request->activa == 1){
            try {
                // Remueve los últimos 5 caracteres de 'forma_pago'
                $gastoo = substr($gasto->gasto, 0, -6);


                // Verifica si 'forma_pago'  son únicos
                $isFormaPagoUnique = !Gasto::where('gasto', $gastoo)
                    ->where('id', '!=', $gasto->id)
                    ->where('activo', 1) // Verificar solo entre los registros activos
                    ->exists();

                if (!$isFormaPagoUnique) {
                    // Almacena el mensaje de error en la sesión y redirige de vuelta
                    return response()->json([
                        'swal' => [
                            'icon' => "error",
                            'title' => "Error en la operación",
                            'text' => "El gasto ya existe. Por favor, elija otro.",
                            'customClass' => [
                                'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                            ],
                            'buttonsStyling' => false
                        ],
                        'error' => "El gasto ya existe. Por favor, elija otro.",
                    ], 400);
                }

                // Actualiza los campos necesarios
                $gasto->update([
                    'gasto' => $gastoo,
                    'activo' => 1
                ]);

                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "El gasto se activo correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'El gasto se activo correctamente.'
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
            $gasto = Gasto::findorfail($id);

            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $gasto->update([
                'gasto' => $gasto->gasto.'-'.substr(str_shuffle($permitted_chars), 0, 5),
                'activo' => 0
            ]);

            return response()->json([
                'swal' => [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El gasto se eliminó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ],
                'success' => 'El gasto se eliminó correctamente.'
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

    public function gasto_index_ajax(Request $request)
    {
        if ($request->origen == 'gasto.index') {

            $gasto = Gasto::with('tipoGasto')->get()
                ->map(function ($item) {

                    // --- Etiqueta de Matriz ---
                $es_activo = $item->activo == 1
                    ? '<span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">Activo</span>'
                    : '<span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded">Eliminado</span>';

                return [
                    'id'        => $item->id,
                    'gasto' => '$' . number_format($item->gasto, 2, '.', ','),
                    'tipo_gasto' => $item->tipoGasto->tipo_gasto,
                    'tipo_gasto_id' => $item->tipo_gasto_id,
                    'es_activo' => $es_activo,
                    'acciones' => e(view('gasto.partials.acciones', compact('item'))->render()),
                ];
            });

            return response()->json([ 'data' => $gasto ]);
        }
    }
}
