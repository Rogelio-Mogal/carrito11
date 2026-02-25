<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:roles.ver')
        ->only(['index', 'show']);

        $this->middleware('permission:roles.crear')
            ->only(['create', 'store']);

        $this->middleware('permission:roles.editar')
            ->only(['edit', 'update']);

        $this->middleware('permission:roles.eliminar')
            ->only(['destroy']);
    }

    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $role = new Role;
        $permissions = Permission::all();
        $metodo = 'create';
        return view('roles.create', compact('role','metodo','permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:roles,name'],
            'permissions' => 'nullable|array',
        ]);

        try{
            $role = Role::create([
                'name' => $request->name
            ]);

            $role->permissions()->sync($request->permissions);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Operación correcta',
                'text' => 'El rol se creó correctamente.',
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.roles.index');
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

    public function show(Role $role)
    {
        //
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $metodo = 'edit';
        return view('roles.edit', compact('role','metodo','permissions'));
    }

    public function update(Request $request, Role $role)
    {

        $request->validate([
            'name' => ['required', 'unique:roles,name,' . $role->id],
            'permissions' => 'nullable|array',
        ]);

        try{
            $role->update($request->all());

            $role->permissions()->sync($request->permissions);

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El rol se actualizó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.roles.index');
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

    public function destroy($id)//Role $role)
    {
        try {
            $rol = Role::findorfail($id);

            $rol->delete();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => 'El rol se ha eliminado',
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            session()->flash('swal', [
                'icon' => "error",
                'title' => "Operación fallida",
                'text' => 'Hubo un error durante el proceso, por favor intente más tarde.',
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
}
