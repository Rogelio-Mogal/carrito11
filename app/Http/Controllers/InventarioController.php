<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\Sucursal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        $sucursales = Sucursal::where('activo', 1)->get();
        $usuario = auth()->user(); // Usuario autenticado
        $sucursalUsuario = $usuario->sucursal_id; // La sucursal asignada al usuario

        return view('inventario.index',compact('sucursales', 'sucursalUsuario'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $inventario = Inventario::with([
            'producto', 
            'producto.marca_c', 
            'producto.familia_c', 
            'producto.subFamilia_c'
        ])->where('producto_id', $id)->first();
        $metodo = 'edit';
        
        if (!$inventario) {
            return redirect()->route('admin.inventario.index');
        }
        return view('inventario.edit', compact('inventario','metodo'));
    }

    public function update(Request $request, $id)
    {
        $inventario = Inventario::findOrFail($id);
        $inventarioId = $inventario->id;

        // Guardar los valores anteriores para compararlos
        $old_inventario = $inventario->cantidad;
        $old_apartado = $inventario->producto_apartado;
        $old_servicio = $inventario->producto_servicio;

        // Validar y actualizar los datos del inventario
        $validatedData = $request->validate([
            'cantidad' => 'nullable|integer|min:0',
            'producto_apartado' => 'nullable|integer|min:0',
            'producto_servicio' => 'nullable|integer|min:0',
            'precio_costo' => 'nullable|numeric|min:0',
            'precio_publico' => 'nullable|numeric|min:0',
            'precio_medio_mayoreo' => 'nullable|numeric|min:0',
            'precio_mayoreo' => 'nullable|numeric|min:0',
            'descripcion' => 'required|string|min:2|max:1500',
        ]);

        try {
            DB::beginTransaction();
            // ACTUALIZO EL INVENTARIO
            // Asignar valores nuevos a la entidad
            $updated = false;
            if ($request->has('cantidad') && $request->cantidad != $old_inventario) {
                $inventario->cantidad = $request->cantidad;
                $updated = true;
            }
            if ($request->has('producto_apartado') && $request->producto_apartado != $old_apartado) {
                $inventario->producto_apartado = $request->producto_apartado;
                $updated = true;
            }
            if ($request->has('producto_servicio') && $request->producto_servicio != $old_servicio) {
                $inventario->producto_servicio = $request->producto_servicio;
                $updated = true;
            }
            if ($request->has('precio_costo')) {
                $inventario->precio_costo = $request->precio_costo;
                $inventario->precio_anterior = $request->precio_costo;
                $updated = true;
            }
            if ($request->has('precio_publico')) {
                $inventario->precio_publico = $request->precio_publico;
                $updated = true;
            }
            if ($request->has('precio_medio_mayoreo')) {
                $inventario->precio_medio_mayoreo = $request->precio_medio_mayoreo;
                $updated = true;
            }
            if ($request->has('precio_mayoreo')) {
                $inventario->precio_mayoreo = $request->precio_mayoreo;
                $updated = true;
            }
            $inventario->updated_at = Carbon::now();

            // Solo guardar si hubo cambios
            if ($updated) {
                $inventario->save();
            }

            $ajuste = $request->ajuste_inventario == 1 ? '(ajuste por inventario)' : '';

            if ($updated) {
                // Registrar el cambio en `cantidad`
                if ($old_inventario !== $inventario->cantidad) {
                    $tipoMovimiento = $inventario->cantidad > $old_inventario ? 'ENTRADA' : 'SALIDA';
                    $debe = $inventario->cantidad > $old_inventario ? $inventario->cantidad - $old_inventario : 0;
                    $haber = $inventario->cantidad < $old_inventario ? $old_inventario - $inventario->cantidad : 0;
                    Kardex::create([
                        'producto_id' => $inventario->producto_id,
                        'movimiento_id' => $inventarioId, 
                        'tipo_movimiento' => $tipoMovimiento,
                        'tipo_detalle' => 'INVENTARIO',
                        'fecha' => now(),
                        'folio' => 'STOCK-' . now()->timestamp,
                        'descripcion' => 'Cambio en stock de ' . $old_inventario . ' a ' . $inventario->cantidad.' '.$ajuste. ' . '.$request->descripcion,
                        'debe' => $debe,
                        'haber' => $haber,
                        'saldo' => $inventario->cantidad,
                        'wci' => auth()->user()->id,
                    ]);
                }

                // Registrar el cambio en `producto_apartado`
                if ($old_apartado !== $inventario->producto_apartado) {
                    $tipoMovimiento = $inventario->producto_apartado > $old_apartado ? 'ENTRADA' : 'SALIDA';
                    $debe = $inventario->producto_apartado > $old_apartado ? $inventario->producto_apartado - $old_apartado : 0;
                    $haber = $inventario->producto_apartado < $old_apartado ? $old_apartado - $inventario->producto_apartado : 0;
                    Kardex::create([
                        'producto_id' => $inventario->producto_id,
                        'movimiento_id' => $inventarioId, 
                        'tipo_movimiento' => $tipoMovimiento,
                        'tipo_detalle' => 'APARTADO',
                        'fecha' => now(),
                        'folio' => 'APART-' . now()->timestamp,
                        'descripcion' => 'Cambio en apartado de ' . $old_apartado . ' a ' . $inventario->producto_apartado.' '.$ajuste. ' . '.$request->descripcion,
                        'debe' => $debe,
                        'haber' => $haber,
                        'saldo' => $inventario->cantidad,
                        'wci' => auth()->user()->id, 
                    ]);
                }

                // Registrar el cambio en `producto_servicio`
                if ($old_servicio !== $inventario->producto_servicio) {
                    $tipoMovimiento = $inventario->producto_servicio > $old_servicio ? 'ENTRADA' : 'SALIDA';
                    $debe = $inventario->producto_servicio > $old_servicio ? $inventario->producto_servicio - $old_servicio : 0;
                    $haber = $inventario->producto_servicio < $old_servicio ? $old_servicio - $inventario->producto_servicio : 0;
                    Kardex::create([
                        'producto_id' => $inventario->producto_id,
                        'movimiento_id' => $inventarioId, 
                        'tipo_movimiento' => $tipoMovimiento,
                        'tipo_detalle' => 'SERVICIO',
                        'fecha' => now(),
                        'folio' => 'SERV-' . now()->timestamp,
                        'descripcion' => 'Cambio en servicio de ' . $old_servicio . ' a ' . $inventario->producto_servicio.' '.$ajuste. ' . '.$request->descripcion,
                        'debe' => $debe,
                        'haber' => $haber,
                        'saldo' => $inventario->cantidad,
                        'wci' => auth()->user()->id,
                    ]);
                }
            }

            /*
            // Registrar el cambio en `cantidad`
            if ($old_inventario !== $inventario->cantidad) {
                Kardex::create([
                    'producto_id' => $inventario->producto_id,
                    'movimiento_id' => $inventarioId, 
                    'tipo_movimiento' => 'Cambio de Apartado', //ENTRADA, SALIDA
                    'tipo_detalle' => 'INVENTARIO',
                    'fecha' => now(),
                    'folio' => 'STOCK-' . now()->timestamp,
                    'descripcion' => 'Cambio en stock de ' . $old_inventario . ' a ' . $inventario->cantidad,
                    'debe' => $inventario->cantidad,
                    'haber' => $old_inventario,
                    'saldo' => $inventario->cantidad, // Actualiza según sea necesario
                    'activo' => true,
                ]);
            }
            
            // Registrar el cambio en `producto_apartado`
            if ($old_apartado !== $inventario->producto_apartado) {
                Kardex::create([
                    'producto_id' => $inventario->producto_id,
                    'movimiento_id' => $inventarioId, 
                    'tipo_movimiento' => 'Cambio de Apartado', //ENTRADA, SALIDA
                    'tipo_detalle' => 'INVENTARIO',
                    'fecha' => now(),
                    'folio' => 'APART-' . now()->timestamp,
                    'descripcion' => 'Cambio en apartado de ' . $old_apartado . ' a ' . $inventario->producto_apartado,
                    'debe' => $inventario->producto_apartado,
                    'haber' => $old_apartado,
                    'saldo' => $inventario->cantidad, // Actualiza según sea necesario
                    'activo' => true,
                ]);
            }

            // Registrar el cambio en `producto_servicio`
            if ($old_servicio !== $inventario->producto_servicio) {
                Kardex::create([
                    'producto_id' => $inventario->producto_id,
                    'movimiento_id' => $inventarioId, 
                    'tipo_movimiento' => 'Cambio de Servicio', //ENTRADA, SALIDA
                    'tipo_detalle' => 'INVENTARIO',
                    'fecha' => now(),
                    'folio' => 'SERV-' . now()->timestamp,
                    'descripcion' => 'Cambio en servicio de ' . $old_servicio . ' a ' . $inventario->producto_servicio,
                    'debe' => $inventario->producto_servicio,
                    'haber' => $old_servicio,
                    'saldo' => $inventario->cantidad, // Actualiza según sea necesario
                    'activo' => true,
                ]);
            }
            */


            DB::commit();
            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "Inventario ajustado.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);
    
            return redirect()->route('admin.inventario.index');
            
        } catch (\Exception $e) {
            DB::rollback();
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

    public function destroy($id)
    {
        //
    }
}
