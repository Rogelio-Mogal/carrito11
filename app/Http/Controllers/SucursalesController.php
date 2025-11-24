<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        return view('sucursales.index');
    }

    public function create()
    {
        $sucursales = new Sucursal();
        $metodo = 'create';
        return view('sucursales.create', compact('metodo','sucursales'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
            'nombre' => 'required|string|max:255|unique:sucursales',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'es_matriz' => 'boolean',
            ],
            [
                // Aquí cambias el texto de los mensajes si quieres algo específico
                'nombre.required' => 'La sucursal es obligatoria.',
                'nombre.unique'   => 'Ya existe una sucursal con ese nombre.',
            ],
            [
                // Aquí cambias el nombre del atributo en TODOS los mensajes
                'nombre' => 'sucursal',
            ]
        );

        try{
            $sucursales = new Sucursal();
            $sucursales->nombre = $request->nombre;
            $sucursales->direccion = $request->direccion;
            $sucursales->telefono = $request->telefono;
            $sucursales->es_matriz = $request->has('es_matriz');
            $sucursales->save();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La sucursal se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.sucursales.index');
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
            $query = $e->getMessage();
            return redirect()->back()
                ->withInput($request->all()) // Aquí solo pasas los valores del formulario
                ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
        }
    }

    public function show(Sucursal $sucursal)
    {
        //
    }

    public function edit($id)
    {
        $sucursales = Sucursal::findorfail($id);
        if($sucursales->activo == 1){
            $metodo = 'edit';
            return view('sucursales.edit', compact('sucursales','metodo'));
        }else{
            return redirect()->route('admin.sucursales.index');
        }
    }

    public function update(Request $request, $id)
    {
    $sucursales = Sucursal::findorfail($id);
        if ($request->activa == 0){

            $request->validate(
                [
                'nombre' => "required|string|max:255|unique:sucursales,nombre,{$sucursales->id}",
                'direccion' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:255',
                'es_matriz' => 'boolean',
                ],
                [
                    // Aquí cambias el texto de los mensajes si quieres algo específico
                    'nombre.required' => 'La sucursal es obligatoria.',
                    'nombre.unique'   => 'Ya existe una sucursal con ese nombre.',
                ],
                [
                    // Aquí cambias el nombre del atributo en TODOS los mensajes
                    'nombre' => 'sucursal',
                ]
            );


            try{
                $sucursales->nombre = $request->nombre;
                $sucursales->direccion = $request->direccion;
                $sucursales->telefono = $request->telefono;
                $sucursales->es_matriz = $request->has('es_matriz');
                $sucursales->save();

                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "La sucursal se actualizó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);

                return redirect()->route('admin.sucursales.index');
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
                ->withInput($request->all()) // Aquí solo pasas los valores del formulario
                ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
            }
        }

        if ($request->activa == 1){
            // Remueve los últimos 5 caracteres de 'full_name' y 'email'
            $name = substr($sucursales->nombre, 0, -6);

            // Verifica si 'full_name' es único
            $isNameUnique = !Sucursal::where('nombre', $name)
            ->where('id', '!=', $sucursales->id)
            ->exists();

            if (!$isNameUnique ) {
                // Almacena el mensaje de error en la sesión y redirige de vuelta
                session()->flash('swal', [
                    'icon' => "error",
                    'title' => "Error en la operación",
                    'text' => "La sucursal ya existen. Por favor, elija otro.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);

                return redirect()->back();
            }

            try{
                // Actualiza los campos necesarios
                $sucursales->update([
                    'nombre' => $name,
                    'activo' => 1
                ]);

                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "La sucursal se activo correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);
                return redirect()->back();
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
                ->withInput($request->all()) // Aquí solo pasas los valores del formulario
                ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
            }
        }
    }

    public function destroy($id)
    {
        try {
            $sucursales = Sucursal::findorfail($id);
            if($sucursales->id > 0){
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

                $sucursales->update([
                    'nombre' => $sucursales->nombre.'-'.substr(str_shuffle($permitted_chars), 0, 5),
                    'activo' => 0
                ]);
            }else{
                return redirect()->route('admin.sucursales.index');
            }

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La sucursal se eliminó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

        } catch (\Exception $e) {
            $query = $e->getMessage();
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

    public function sucursal_index_ajax(Request $request)
    {
        if ($request->origen == 'sucursal.index') {

            $sucursales = Sucursal::all()->map(function ($item) {

                // --- Etiqueta de Matriz ---
                $es_matriz = $item->es_matriz == 1
                    ? '<span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">Si</span>'
                    : '<span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded">No</span>';

                return [
                    'id'        => $item->id,
                    'nombre'    => $item->nombre,
                    'direccion' => $item->direccion,
                    'telefono'  => $item->telefono,
                    'es_matriz' => $es_matriz,
                    'acciones' => e(view('sucursales.partials.acciones', compact('item'))->render()),
                ];
            });

            return response()->json([ 'data' => $sucursales ]);
        }
    }
}
