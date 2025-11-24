<?php

namespace App\Http\Controllers;

use App\Models\Atributo;
use App\Models\FamiliaAtributo;
use App\Models\ProductoCaracteristica;
use Illuminate\Http\Request;

class FamiliaAtributoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
         return view('familia_atributo.index');
    }

    public function create()
    {
        $famAtributo = new ProductoCaracteristica();
        $familias = ProductoCaracteristica::where('tipo', 'SUB_FAMILIA')->get();
        $atributos = Atributo::where('activo', 1)->get();

        $metodo = 'create';
        return view('familia_atributo.create', compact('metodo', 'famAtributo','familias','atributos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'familia_id' => 'required|exists:producto_caracteristicas,id',
            'atributo_id' => 'required|array',
            'atributo_id.*' => 'exists:atributos,id',
        ]);

        try {

            //FamiliaAtributo::create($data);

            $fam = ProductoCaracteristica::find($request->familia_id);
            
            $fam->atributos()->syncWithPivotValues($request->atributo_id, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "Familia-Atributos se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.familia.atributos.index');
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

    public function show(FamiliaAtributo $familiaAtributo)
    {
        //
    }

    public function edit(FamiliaAtributo $familiaAtributo)
    {
        // cargamos la familia con sus atributos relacionados
        $famAtributo = ProductoCaracteristica::with('atributos')->findOrFail($familiaAtributo->familia_id);

        $familias = ProductoCaracteristica::where('tipo', 'SUB_FAMILIA')->get();
        $atributos = Atributo::where('activo', 1)->get();

        return view('familia_atributo.edit', [
            'famAtributo' => $famAtributo,
            'familiaAtributo'  => $familiaAtributo,
            'familias'    => $familias,
            'atributos'   => $atributos,
            'metodo'      => 'edit'
        ]);
    }

    public function update(Request $request, FamiliaAtributo $familiaAtributo)
    {
        $request->validate([
            'familia_id' => 'required|exists:producto_caracteristicas,id',
            'atributo_id' => 'array',
            'atributo_id.*' => 'exists:atributos,id'
        ]);

        try {

            $familia = ProductoCaracteristica::findOrFail($request->familia_id);
            $familia->atributos()->sync($request->atributo_id ?? []);

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "Familia-Atributos se modificó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.familia.atributos.index');
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

    public function destroy(FamiliaAtributo $familiaAtributo)
    {
        try {
            $familiaAtributo->delete();

            return response()->json([
                'swal' => [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "Atributo eliminado correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ],
                'success' => 'Atributo eliminado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el atributo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function familia_atributo_index_ajax(Request $request)
    {
        // TODOS LOS PRODUCTOS PARA EL INDEX 
        if ($request->origen == 'familia.atributo.index') {
            
            $atributo = FamiliaAtributo::with(['familia', 'atributo'])->get();

            return response()->json(['data' => $atributo]);
        }

        // PRODUCTOS PARA EL APARTADO DE COMPRAS (SOLO PRODUCTOS)
        if ($request->origen == 'atributo.compras') {
            $atributo = FamiliaAtributo::where('id', '!=', 1)
                ->where('activo', 1)
                ->where('tipo', '=', 'PRODUCTO')
                ->with(['inventario'])
                ->get();

            return response()->json(['data' => $atributo]);
        }

        // PRODUCTOS PARA EL APARTADO DE INVENTARIO (SOLO PRODUCTOS)
        if ($request->origen == 'atributo.inventario') {
            $atributo = FamiliaAtributo::where('id', '!=', 1)
                ->where('activo', 1)
                ->where('tipo', '=', 'PRODUCTO')
                ->with(['inventario'])
                ->get();

            return response()->json(['data' => $atributo]);
        }

        // PRODUCTOS PARA EL APARTADO DE COTIZACIONES (SOLO PRODUCTOS)
        if ($request->origen == 'atributo.cotizaciones') {
            $atributo = FamiliaAtributo::where('id', '!=', 1)
                ->where('activo', 1)
                ->where('tipo', '=', 'PRODUCTO')
                ->with(['inventario'])
                ->get();

            return response()->json(['data' => $atributo]);
        }
    }
}
