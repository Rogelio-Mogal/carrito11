<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:proveedores.ver')
        ->only(['index', 'show']);

        $this->middleware('permission:proveedores.crear')
            ->only(['create', 'store']);

        $this->middleware('permission:proveedores.editar')
            ->only(['edit', 'update']);

        $this->middleware('permission:proveedores.eliminar')
            ->only(['destroy']);
    }

    public function index()
    {
        $proveedores = Proveedor::where('id', '!=', 1)->get();
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        $proveedor = new Proveedor();
        $metodo = 'create';
        return view('proveedores.create', compact('metodo','proveedor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor' => 'required|string|max:255|unique:proveedores',
            'telefono' => 'nullable|string|max:255',
            'correo' => 'nullable|string|email|max:255|unique:proveedores',
        ]);

        try{
            $proveedor = new Proveedor();
            $proveedor->proveedor = $request->proveedor;
            $proveedor->telefono = $request->telefono;
            $proveedor->correo = $request->correo;
            $proveedor->wci = auth()->user()->id;
            $proveedor->save();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El proveedor se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.proveedores.index');
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

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $proveedor = Proveedor::findorfail($id);
        if($proveedor->activo == 1 && $proveedor->id > 1){
            $metodo = 'edit';
            return view('proveedores.edit', compact('proveedor','metodo'));
        }else{
            return redirect()->route('admin.proveedores.index');
        }
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findorfail($id);
        if ($request->activa == 0){

            $request->validate([
                'proveedor' => "required|string|max:255|unique:proveedores,proveedor,{$proveedor->id}",
                'telefono' => 'nullable|string|max:255',
                'correo' => "nullable|string|email|max:255|unique:proveedores,correo,{$proveedor->id}",
            ]);


            try{
                $proveedor->proveedor = $request->proveedor;
                $proveedor->telefono = $request->telefono;
                $proveedor->correo = $request->correo;
                $proveedor->save();

                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El proveedor se actualizó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);

                return redirect()->route('admin.proveedores.index');
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
            $name = substr($proveedor->proveedor, 0, -6);
            $email = substr($proveedor->correo, 0, -6);

            // Verifica si 'full_name' y 'email' son únicos
            $isFullNameUnique = !Proveedor::where('proveedor', $name)->where('id', '!=', $proveedor->id)->exists();
            $isEmailUnique = !Proveedor::where('correo', $email)->where('id', '!=', $proveedor->id)->exists();

            if (!$isFullNameUnique || !$isEmailUnique) {
                // Almacena el mensaje de error en la sesión y redirige de vuelta
                session()->flash('swal', [
                    'icon' => "error",
                    'title' => "Error en la operación",
                    'text' => "El proveedor o el correo electrónico ya existen. Por favor, elija otro.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);

                return redirect()->back();
            }

            try{
                // Actualiza los campos necesarios
                $proveedor->update([
                    'proveedor' => $name,
                    'correo' => $email,
                    'activo' => 1
                ]);

                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El proveedor se activo correctamente.",
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
            $proveedor = Proveedor::findorfail($id);
            if($proveedor->id > 1){
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

                $proveedor->update([
                    'proveedor' => $proveedor->proveedor.'-'.substr(str_shuffle($permitted_chars), 0, 5),
                    'correo' => $proveedor->correo.'-'.substr(str_shuffle($permitted_chars), 0, 5),
                    'activo' => 0
                ]);
            }else{
                return redirect()->route('admin.proveedores.index');
            }

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El proveedor se eliminó correctamente.",
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

    public function proveedor_index_ajax(Request $request)
    {

        if ($request->origen == 'proveedor.index') {

            $proveedores = Proveedor::where('id', '!=', 1)->get()
                ->map(function ($item) {

                // --- Etiqueta de Matriz ---
                $es_activo = $item->activo == 1
                    ? '<span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">Activo</span>'
                    : '<span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded">Eliminado</span>';

                return [
                    'id'        => $item->id,
                    'proveedor' => $item->proveedor,
                    'correo' => $item->correo,
                    'telefono' => $item->telefono,
                    'es_activo' => $es_activo,
                    'acciones' => e(view('proveedores.partials.acciones', compact('item'))->render()),
                ];
            });

            return response()->json([ 'data' => $proveedores ]);
        }
    }
}
