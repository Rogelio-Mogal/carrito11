<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\DocumentoDetalle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CotizacionesDetallesController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
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
        $cotizacion = Documento::findorfail($request->documento_id);
        $cotizacionId = $cotizacion->id;

        // CREAMOS EL COTIZACION DETALLES
        $totalCompra = 0;

        $importe = $request->importe;

        $nameComun = '';
        if($request->is_producto_comun == '1'){
            //PRODUCTO COMÚN
            $nameComun = $request->product_name;
        }

        $data = array(
            'documento_id' => $cotizacionId,
            'producto_id' => $request->producto_id,
            'producto_comun' => $nameComun,
            'cantidad' => $request->cantidad,
            'precio' => $request->precio,
            'precio_publico' => $request->precio_publico,
            'precio_medio_mayoreo' => $request->precio_medio_mayoreo,
            'precio_mayoreo' => $request->precio_mayoreo,
            'importe' => $request->importe,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        );
        $cotizacionDetalle = DocumentoDetalle::insert($data);

        // Acumulo el importe en el total de la compra
        // ACTUALIZAMOS EL TOTAL DE LA COTIZACIÓN
        $totImporte = DocumentoDetalle::where('documento_id','=',$cotizacionId)
        ->where('activo',1)
        ->sum('importe');

        // Actualizo el total de la compra
        $cotizacion->total = $totImporte;
        $cotizacion->save();

        return json_encode($cotizacionDetalle);
    }

    public function show($id)
    {

        /*$cotizacionDetalle = Documento::with(['detallesDocumentos.productoDocumento','clienteDocumento'])
        ->where('id',$id)
        ->where('documento_detalles.activo',1)
        ->first();*/

        $cotizacionDetalle = Documento::with(['detallesDocumentos' => function($query) {
            $query->where('activo', 1);
        }, 'detallesDocumentos.productoDocumento', 'clienteDocumento'])
        ->where('id', $id)
        ->first();

        //return json_encode($cotizacionDetalle);
        return response()->json($cotizacionDetalle);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        \DB::beginTransaction();
        try {

            if( $request->cantidad > 0 && $request->tipo == 'actualiza-cantidad' ){

                $detalle = DocumentoDetalle::where('id','=',$id)
                    ->first();

                $cotizacion = Documento::select('id')
                    ->where('id','=',$detalle->documento_id)
                    ->first();

                // IMPORTE DEL DETALLE POR TIPO DE CLIENTE
                $importe = 0;
                if ($request->tipo_precio == 'CLIENTE PÚBLICO' ){
                    $importe = $detalle->precio_publico * $request->cantidad;
                }
                if ($request->tipo_precio == 'CLIENTE MAYOREO'){
                    $importe = $detalle->precio_mayoreo * $request->cantidad;
                }
                if ($request->tipo_precio == 'CLIENTE MEDIO MAYOREO'){
                    $importe = $detalle->precio_medio_mayoreo * $request->cantidad;
                }

                $detalle->update([
                    'cantidad'  =>  $request->cantidad,
                    'importe' => $importe,
                ]);

                // ACTUALIZAMOS EL TOTAL DE LA COTIZACIÓN
                $totImporte = DocumentoDetalle::where('documento_id','=',$cotizacion->id)
                    ->where('activo',1)
                    ->sum('importe');

                $cotizacion->update([
                    'total'  =>  $totImporte,
                ]);

                \DB::commit();
                return json_encode($detalle);
            }else if ( $request->tipo == 'actualiza-precio-unitario' ){
                $cotizacionDetalle = DocumentoDetalle::findorfail($id);

                $cotizacion = Documento::where('id','=',$cotizacionDetalle->documento_id)
                    ->first();

                $cotizacionDetalle->update([
                    'precio' => $request->precio,
                    'precio_publico' => $request->precio,
                    'precio_medio_mayoreo' => $request->precio,
                    'precio_mayoreo' => $request->precio,
                    'importe' => $cotizacionDetalle->cantidad * $request->precio,
                ]);

                // ACTUALIZAMOS EL TOTAL DE LA COTIZACIÓN
                $totImporte = DocumentoDetalle::where('documento_id','=',$cotizacion->id)
                    ->where('activo',1)
                    ->sum('importe');

                $cotizacion->update([
                    'total'  =>  $totImporte,
                ]);

                \DB::commit();
                return json_encode($cotizacionDetalle);
            }else if ( $request->tipo == 'actualiza-precios' ){
                $cotizacion = Documento::findorfail($id);

                $cotizacionDetalle = DocumentoDetalle::where('documento_id','=',$cotizacion->id)
                    ->where('activo',1)
                    ->get();
                // Verificar si hay elementos en la colección
                if ($cotizacionDetalle->isNotEmpty()) {
                    foreach ($cotizacionDetalle as $key => $value) {
                        if($request->tipo_precio == 'CLIENTE PÚBLICO'){
                            $value->update([
                                'precio' => $value->precio_publico,
                                'importe' => $value->cantidad * $value->precio_publico,
                            ]);
                        }else if ($request->tipo_precio == 'CLIENTE MEDIO MAYOREO'){
                            $value->update([
                                'precio' => $value->precio_medio_mayoreo,
                                'importe' => $value->cantidad * $value->precio_medio_mayoreo,
                            ]);
                        }else if($request->tipo_precio == 'CLIENTE MAYOREO'){
                            $value->update([
                                'precio' => $value->precio_mayoreo,
                                'importe' => $value->cantidad * $value->precio_mayoreo,
                            ]);
                        }
                    }

                    // ACTUALIZAMOS EL TOTAL DE LA COTIZACIÓN
                    $totImporte = DocumentoDetalle::where('documento_id','=',$cotizacion->id)
                        ->where('activo',1)
                        ->sum('importe');

                    $cotizacion->update([
                        'tipo_precio' => $request->tipo_precio,
                        'total'  =>  $totImporte,
                    ]);

                    \DB::commit();
                    //return json_encode($cotizacionDetalle);
                } else {
                    // Manejar caso cuando no hay detalles de cotización
                    \DB::rollBack();
                    return response()->json(['error' => 'No hay detalles para actualizar.'], 400);
                }
                return json_encode($cotizacionDetalle);
            }

        } catch (\Exception $e) {
            \DB::rollback();
            $query = $e->getMessage();
            return json_encode($query);

            return \Redirect::back()
                ->with(['receta' => 'fail-destroy', 'error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            \DB::beginTransaction();

            // Obtenemos el detalle del documento
            $detalle = DocumentoDetalle::select('documento_id','activo', 'id')->find($id);
            // Si el detalle existe
            if ($detalle) {
                $documentoId = $detalle->documento_id;

                // Desactivamos el detalle
                $detalle->update(['activo' => 0]);

                // Recalculamos el total de la cotización
                $totImporte = DocumentoDetalle::where('documento_id', $documentoId)
                    ->where('activo', 1)
                    ->sum('importe');

                // Actualizamos el total de la cotización
                Documento::where('id', $documentoId)->update(['total' => $totImporte]);

                \DB::commit();

                return json_encode($detalle);
            }
            \DB::rollBack();
            return response()->json(['error' => 'Detalle no encontrado'], 404);
        } catch (\Exception $e) {
            \DB::rollback();
            $query = $e->getMessage();
            return json_encode($query);

            return \Redirect::back()
                ->with(['receta' => 'fail-destroy', 'error' => $e->getMessage()]);
        }
    }
}
