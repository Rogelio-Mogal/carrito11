<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\ProductoCaracteristica;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreciosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        $precios = Precio::select('precios.*','producto_caracteristicas.nombre')
        ->leftJoin('producto_caracteristicas', 'precios.producto_caracteristica_id', '=', 'producto_caracteristicas.id')
        ->where('precios.activo',1)
        ->orderBy('id', 'asc')
        ->get();

        return view('precios.index', compact('precios'));
    }

    public function create(Request $request)
    {
        $precio = new Precio;
        $metodo = 'create';
        $tipoPrecio = $request->input('precio');

        $subfamilia = ProductoCaracteristica::where('tipo', 'SUB_FAMILIA')
            ->where('activo', 1)
            ->where('id', '>', 5)
            ->select('id', 'nombre')
            ->orderBy('nombre')
            ->get();

        return view('precios.create',compact('precio','metodo','tipoPrecio','subfamilia'));
    }

    public function store(Request $request)
    {
        foreach ($request->desde as $key => $value) {
            if( $request->tipo_precio[$key] == 1 ){
                Precio::create([
                    'producto_caracteristica_id' => 3,
                    'desde' => $request->desde[$key],
                    'hasta' => $request->hasta[$key],
                    'porcentaje_publico' => $request->porcentaje_publico[$key],
                    'porcentaje_medio'   => $request->porcentaje_medio[$key],
                    'porcentaje_mayoreo' => $request->porcentaje_mayoreo[$key],
                    'tipo_precio' => $request->tipo_precio[$key],
                    'precio' => $request->tipo,
                ]);
            }else if( $request->tipo_precio[$key] == 2 ){
                Precio::create([
                    'producto_caracteristica_id' => $request->producto_caracteristica_id,
                    'desde' => $request->desde[$key],
                    'hasta' => $request->hasta[$key],
                    'especifico_publico' => $request->especifico_publico[$key],
                    'especifico_medio'   => $request->especifico_medio[$key],
                    'especifico_mayoreo' => $request->especifico_mayoreo[$key],
                    'tipo_precio' => $request->tipo_precio[$key],
                    'precio' => 'INTERNO',
                ]);
            }
        }

        session()->flash('swal', [
            'icon' => "success",
            'title' => "Operación correcta",
            'text' => "El precio se creó correctamente.",
            'customClass' => [
                'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
            ],
            'buttonsStyling' => false
        ]);

        return redirect()->route('admin.precios.index');
    }

    public function show($id)
    {
        $precio = Precio::find($id);
        return response()->json(['data' => $precio]);
    }

    public function edit($id)
    {
        $precio = Precio::find($id);
        $metodo = 'edit';
        return view('precios.edit', compact('precio','metodo'));
    }

    public function update(Request $request, $id)
    {
        $precio = Precio::find($id);
        if( $request->tipo_precio == 1 ){
            $precio->producto_caracteristica_id = 3;
            $precio->porcentaje_publico = $request->get('porcentaje_publico');
            $precio->porcentaje_medio = $request->get('porcentaje_medio');
            $precio->porcentaje_mayoreo = $request->get('porcentaje_mayoreo');
            $precio->save();
        }else if( $request->tipo_precio == 2 ){
            $precio->especifico_publico = $request->get('especifico_publico');
            $precio->especifico_medio = $request->get('especifico_medio');
            $precio->especifico_mayoreo = $request->get('especifico_mayoreo');
            $precio->save();
        }

        session()->flash('swal', [
            'icon' => "success",
            'title' => "Operación correcta",
            'text' => "El precio se actualizó correctamente.",
            'customClass' => [
                'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
            ],
            'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
        ]);
        return redirect('precios');
    }

    public function destroy($id)
    {
        //
    }

    public function comparaRangosPrecios(Request $request)
    {
        $validatedData = $request->validate([
            // Aquí van las demás validaciones de los campos
            'desde' => 'required|numeric',
            'hasta' => 'required|numeric|gt:desde', // Asegura que hasta sea mayor que desde
        ]);
    
        // Obtener el último registro de la tabla precios
        $tipoPrecio = $request->input('tipo_precio');
        $precio = $request->input('precio');
        if($tipoPrecio == 1){
            $ultimoPrecio = Precio::where('tipo_precio', $tipoPrecio)->where('precio', $precio)->orderBy('hasta', 'desc')->first();
        }else{
            $ultimoPrecio = Precio::where('tipo_precio', $tipoPrecio)->where('precio', 'INTERNO')
            ->where('producto_caracteristica_id','=', $request->input('id'))
            ->orderBy('hasta', 'desc')->first();
            //dd($ultimoPrecio);
        }
        $ultimoHasta = $ultimoPrecio ? $ultimoPrecio->hasta : 0;
        $siguienteDesde = $ultimoHasta + 1;
    
        // Validar que el rango sea consecutivo
        $desde = $request->input('desde');
        if ($desde != $siguienteDesde) {
            $mensaje = 'El rango debe comenzar desde ' . $siguienteDesde;
            return json_encode(['error'=> $mensaje, 'rango'=>$siguienteDesde]);
        }
    
        // Resto del código para validar solapamiento y guardar el registro en la base de datos
        $mensaje = 'No esta repetido';
        return json_encode(['success'=> $mensaje,'rango'=>$siguienteDesde]);
    }

    /*public function obtenerPrecios2(Request $request)
    {
        $precio = $request->input('precio');

        // Buscamos el precio dentro del rango "desde-hasta" solo para producto_caracteristica_id = 3
        $precioRegistro = Precio::where('producto_caracteristica_id', 3)
            ->where('desde', '<=', $precio)
            ->where('hasta', '>=', $precio)
            ->where('precio', 'INTERNO')
            ->first();

        $tipo = 'general';

        return response()->json([
            'precio' => $precioRegistro,
            'tipo' => $tipo
        ]);
    }*/

    public function obtenerPrecios(Request $request)
    {
        $precio = $request->input('precio');
        $productoId = $request->input('id');

        //Buscamos la subfamilia del producto
        $vacio = 0;
        $tipo = '';
        try {
            // La consulta no está vacía, $subfamilia contiene el resultado
            $subfamilia = Producto::findOrFail($productoId);
            $id = $subfamilia->sub_familia;

            if ($id !== null && $id !== '' && $id > 3) {
                // El valor de $id es mayor que 0 y no es null ni vacío
                $tipo = 'especifico';
                $precios = Precio::where('desde', '<=', $precio)
                    ->where('hasta', '>=', $precio)
                    ->where('precio', 'INTERNO')
                    ->where('producto_caracteristica_id', $id)
                    ->first();
   

                if ($precios === null ){ //|| $precios->isEmpty()) {
                    // La consulta está vacía
                    $vacio = 1;
                }else{
                    return json_encode(['precio' => $precios, 'tipo' => $tipo]);
                }
            } else {
                $vacio = 1;
                // El valor de $id es null, vacío o no es mayor que 0
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // La consulta está vacía, no se encontró ningún registro con el ID especificado
            $tipo = 'general';
            $precios = Precio::where('desde', '<=', $precio)
                ->where('hasta', '>=', $precio)
                ->where('precio', 'INTERNO')
                ->where('producto_caracteristica_id', 3)
                ->first();
            return json_encode(['precio' => $precios, 'tipo' => $tipo]);
        }
        if($vacio == 1){
            $tipo = 'general';
            $precios = Precio::where('desde', '<=', $precio)
                ->where('hasta', '>=', $precio)
                ->where('precio', 'INTERNO')
                ->where('producto_caracteristica_id', 3)
                ->first();
        }

        return json_encode(['precio' => $precios, 'tipo' => $tipo]);
    }
}
