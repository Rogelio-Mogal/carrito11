<?php

namespace App\Http\Controllers;

use App\Models\Atributo;
use Illuminate\Http\Request;

class AtributoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:atributos.ver')
        ->only(['index', 'show']);

        $this->middleware('permission:atributos.crear')
            ->only(['create', 'store']);

        $this->middleware('permission:atributos.editar')
            ->only(['edit', 'update']);

        $this->middleware('permission:atributos.eliminar')
            ->only(['destroy']);
    }

    public function index()
    {
        return view('atributo.index');
    }

    public function create()
    {
        $atributo = new Atributo();

        $metodo = 'create';
        return view('atributo.create', compact('metodo', 'atributo'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_campo' => 'required|in:texto,numero,select,multiselect',
            'opciones' => 'nullable|string',
        ]);

        try {

            // Convertir opciones separadas por comas a JSON
            /*if (!empty($data['opciones'])) {
                $data['opciones'] = array_map('trim', explode(',', $data['opciones']));
            } else {
                $data['opciones'] = null;
            }*/

            // Convertir string separadas por comas en array
            if (!empty($data['opciones'])) {
                // "USB 2.0,USB 3.0,USB 3.1" → ["USB 2.0","USB 3.0","USB 3.1"]
                $data['opciones'] = array_map('trim', explode(',', $data['opciones']));
            } else {
                $data['opciones'] = null;
            }

            Atributo::create($data);

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El atributo se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.atributos.index');
        } catch (\Exception $e) {
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

    public function show(Atributo $atributo)
    {
        //
    }

    public function edit(Atributo $atributo)
    {

        if($atributo->activo == 1){
            $metodo = 'edit';
            return view('atributo.edit', compact('atributo','metodo'));
        }else{
            return redirect()->route('admin.atributos.index');
        }
    }

    public function update(Request $request, Atributo $atributo)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_campo' => 'required|in:texto,numero,select,multiselect',
            'opciones' => 'nullable|string',
        ]);

        try {

            // Convertir string separadas por comas en array
            if (!empty($data['opciones'])) {
                // "USB 2.0,USB 3.0,USB 3.1" → ["USB 2.0","USB 3.0","USB 3.1"]
                $data['opciones'] = array_map('trim', explode(',', $data['opciones']));
            } else {
                $data['opciones'] = null;
            }

            $atributo->update($data);

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El atributo se modificó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.atributos.index');
        } catch (\Exception $e) {
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

    public function destroy(Atributo $atributo)
    {
        try {
            if ($atributo->activo == 0) {
                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "El atributo se eliminó correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'La compra se eliminó correctamente.'
                ], 200);
            }

            /*
            // Es un producto, verifica la existencia en el inventario
            $inventario = $atributo->inventario;

            if ($inventario && $inventario->cantidad > 0) {
                // Si el producto tiene stock, no permitir la actualización/eliminación
                return response()->json([
                    'swal' => [
                        'icon' => "error",
                        'title' => "Operación fallida",
                        'text' => "No se puede eliminar el atributo: " . $atributo->nombre . " porque se encuentra asignado a un producto.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'error' => 'No se puede eliminar el atributo: ' . $atributo->nombre . ' porque se encuentra asignado a un producto.'
                ], 400);
            }
            */


            // Si es un servicio o un producto sin stock, procede con la actualización/eliminación
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $atributo->update([
                'nombre' => $atributo->nombre . '-' . substr(str_shuffle($permitted_chars), 0, 5),
                'activo' => 0
            ]);

            // Respuesta exitosa
            return response()->json([
                'swal' => [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El atributo se eliminó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ],
                'success' => 'El atributo se eliminó correctamente.'
            ], 200);
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => "error",
                'title' => "Operación fallida",
                'text' => "Hubo un error durante el proceso, por favor intente más tarde.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);
            return redirect()->back()
                ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
        }
    }

    public function atributo_index_ajax(Request $request)
    {
        // TODOS LOS PRODUCTOS PARA EL INDEX DE LA TABLA PRODUCTOS/SERVICIOS
        if ($request->origen == 'atributo.index') {
            $atributo = Atributo::all();

            return response()->json(['data' => $atributo]);
        }

        // PRODUCTOS PARA EL APARTADO DE COMPRAS (SOLO PRODUCTOS)
        if ($request->origen == 'atributo.compras') {
            $atributo = Atributo::where('id', '!=', 1)
                ->where('activo', 1)
                ->where('tipo', '=', 'PRODUCTO')
                ->with(['inventario'])
                ->get();

            return response()->json(['data' => $atributo]);
        }

        // PRODUCTOS PARA EL APARTADO DE INVENTARIO (SOLO PRODUCTOS)
        if ($request->origen == 'atributo.inventario') {
            $atributo = Atributo::where('id', '!=', 1)
                ->where('activo', 1)
                ->where('tipo', '=', 'PRODUCTO')
                ->with(['inventario'])
                ->get();

            return response()->json(['data' => $atributo]);
        }

        // PRODUCTOS PARA EL APARTADO DE COTIZACIONES (SOLO PRODUCTOS)
        if ($request->origen == 'atributo.cotizaciones') {
            $atributo = Atributo::where('id', '!=', 1)
                ->where('activo', 1)
                ->where('tipo', '=', 'PRODUCTO')
                ->with(['inventario'])
                ->get();

            return response()->json(['data' => $atributo]);
        }
    }
}
