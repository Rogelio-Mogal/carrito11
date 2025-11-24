<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }
    
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $perfil)
    {
        //
    }

    public function edit(User $perfil)
    {
        $metodo = 'edit';
        $user = Auth::user();
        $perfil = User::find($user->id);

        return view('users-profile.edit', compact('perfil','metodo'));
    }

    public function update(Request $request, User $perfil)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$perfil->id}",
            'printer_size' => 'required|integer',
            'password' => 'nullable|string|min:8|confirmed',
            'menu_color' => 'nullable|string|max:20',
            'theme' => 'nullable|string|max:12',
        ]);

        // Validación personalizada para full_name
        $fullName = $request->name . ' ' . $request->last_name;
        if (User::where('full_name', $fullName)->where('id', '!=', $perfil->id)->exists()) {
            return back()->withErrors(['full_name' => 'El usuario ya se encuentra registrado.'])->withInput();
        }

        try{
            $perfil->name = $request->name;
            $perfil->last_name = $request->last_name;
            $perfil->full_name = $fullName;
            $perfil->printer_size = $request->printer_size;
            $perfil->email = $request->email;
            $perfil->theme = $request->theme;

            // Actualizar menu_color solo si se seleccionó un nuevo valor
            if ($request->menu_color !== $perfil->menu_color) {
                $perfil->menu_color = $request->menu_color;
                $perfil->theme = 'light';
            }
            
            // Solo actualizar la contraseña si se proporciona una nueva
            if ($request->has('password') && $request->password != '') {
                $perfil->password = bcrypt($request->password);
            }
            $perfil->menu_color = $request->menu_color;

            $perfil->save();


            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El prfil se actualizó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.perfil.edit',$perfil);
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
                ->withInput($request->all()) // Aquí solo pasas los valores del formulario
                ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
        }
    }

    public function destroy(User $perfil)
    {
        //
    }

    public function updateMenuColor(Request $request)
    {
        $request->validate([
            'menu_color' => 'required|string|max:20',
        ]);

        $perfil = Auth::user();
        $perfil->menu_color = $request->menu_color;
        $perfil->theme = 'light';
        $perfil->save();

        return response()->json(['success' => true]);
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|string|in:light,dark',
        ]);

        $perfil = Auth::user();
        $perfil->theme = $request->theme;
        $perfil->save();

        return response()->json(['success' => true]);
    }
}
