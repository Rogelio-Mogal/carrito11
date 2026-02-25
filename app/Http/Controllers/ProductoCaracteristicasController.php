<?php

namespace App\Http\Controllers;
use App\Models\ProductoCaracteristica;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductoCaracteristicasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:producto_caracteristica.ver')
        ->only(['index', 'show']);

        $this->middleware('permission:producto_caracteristica.crear')
            ->only(['create', 'store']);

        $this->middleware('permission:producto_caracteristica.editar')
            ->only(['edit', 'update']);

        $this->middleware('permission:producto_caracteristica.eliminar')
            ->only(['destroy']);
    }

    public function index()
    {
        return view('producto_caracteristica.index');
    }

    public function create()
    {
        $caracteristica = new ProductoCaracteristica();
        $tipoValues = ['MARCA', 'FAMILIA', 'SUB_FAMILIA'];
        $metodo = 'create';
        return view('producto_caracteristica.create', compact('metodo','caracteristica','tipoValues'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:MARCA,FAMILIA,SUB_FAMILIA',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Si el tipo es 'marca', el campo 'imagen' es requerido
        if ($request->input('tipo') === 'MARCA' && !$request->hasFile('imagen')) {
            return redirect()->back()->withErrors(['imagen' => 'La imagen es obligatoria cuando el tipo es marca.'])->withInput();
        }

        // Verificar la unicidad de la combinación nombre y tipo
        if (ProductoCaracteristica::where('nombre', $validatedData['nombre'])->where('tipo', $validatedData['tipo'])->exists()) {
            return redirect()->back()->withErrors(['nombre' => 'El nombre ya existe para este tipo.'])->withInput();
        }

        try {
            $caracteristica = new ProductoCaracteristica();
            $caracteristica->nombre = $request->nombre;
            $caracteristica->tipo = $request->tipo;

            if($request->file('imagen')){
                //$imageStorage = Storage::put('productos', $request->imagen);
                //$caracteristica->imagen = $imageStorage;

                $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen')->getClientOriginalExtension();


                //$imageStorage = Storage::disk('s3')->putFileAs('productos', $request->imagen, $file_name);

                /*
                //CODIGO CORRECTO, FUNCIONA SIN EL PAQIETE
                $imageStorage = Storage::putFileAs('productos', $request->imagen ,$file_name, [
                    'visibility' => 'public',
                ]);
                */


                // CON EL PAQUETE INTERVENTION/IMAGE
                /*
                $imageStorage = Storage::putFileAs('productos', $request->imagen, $file_name, [
                    'visibility' => 'public',
                ]);

                $manager = new ImageManager(new Driver());
                $img = $manager->read('storage/'.$imageStorage);
                $img->scale(1200, null, function($constraint){
                    $constraint->aspectRatio();
                }); // Redimenciona el ancho, alto, ajuste de aspecto (máximo)
                $img->save('storage/'.$imageStorage, null, 'jpg' ); // Ruta de la imagen, Calidad de imagen, trabajara la imagen como jpg pero no la cambiara de extencion
                */

                $imageStorage = Storage::putFileAs('caracteristicas', $request->imagen, $file_name, [
                    'visibility' => 'public',
                ]);

                $imageStorageThumb = Storage::putFileAs('caracteristicas/thumbs', $request->imagen, $file_name, [
                    'visibility' => 'public',
                ]);

                // IMAGEN NORMAL
                $manager = new ImageManager(new Driver());
                $img = $manager->read('storage/'.$imageStorage);
                $img->save('storage/'.$imageStorage, 90, 'jpg' ); // Ruta de la imagen, Calidad de imagen, trabajara la imagen como jpg pero no la cambiara de extencion

                // IMAGEN THUMB
                $imgThumb = $manager->read('storage/'.$imageStorageThumb);
                $imgThumb->scale(null, 210, function($constraint){
                    $constraint->aspectRatio();
                }); // Redimenciona el ancho, alto, ajuste de aspecto (máximo)
                $imgThumb->save('storage/'.$imageStorageThumb, 90, 'jpg' );

                $caracteristica->imagen = $imageStorage;
                $caracteristica->img_thumb = $imageStorageThumb;
            }
            $caracteristica->save();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La característica se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.producto.caracteristica.index');
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

    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        if($id > 4){
            $metodo = 'edit';
            $caracteristica = ProductoCaracteristica::find($id);
            $tipoValues = ['MARCA', 'FAMILIA', 'SUB_FAMILIA'];

            return view('producto_caracteristica.edit', compact('caracteristica','metodo','tipoValues'));
        }else{
            return redirect()->route('admin.producto.caracteristica.index');
        }
    }

    public function update(Request $request, $id)
    {
        $caracteristica = ProductoCaracteristica::findorfail($id);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:MARCA,FAMILIA,SUB_FAMILIA',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Si el tipo es 'marca', el campo 'imagen' es requerido
        if ($request->input('tipo') === 'MARCA' && !$request->hasFile('imagen')) {
            return redirect()->back()->withErrors(['imagen' => 'La imagen es obligatoria cuando el tipo es marca.'])->withInput();
        }

        // Verificar la unicidad de la combinación nombre y tipo
         if (ProductoCaracteristica::where('nombre', $validatedData['nombre'])->where('tipo', $validatedData['tipo'])->where('id', '!=', $caracteristica->id)->exists()) {
            return redirect()->back()->withErrors(['nombre' => 'El nombre ya existe para este tipo.'])->withInput();
        }

        try {
            $caracteristica->nombre = $request->nombre;
            $caracteristica->tipo = $request->tipo;

            if($request->file('imagen')){
                // Eliminamos la imagen anterior
                /*if($caracteristica->imagen){
                    $imageUrl = $caracteristica->imagen;
                    // Extraer solo la ruta del archivo desde la URL
                    $imagePath = parse_url($imageUrl, PHP_URL_PATH);
                    $imagePath = str_replace('storage/', '', $imagePath);

                    if (Storage::disk('s3')->exists($imagePath)) {
                        Storage::disk('s3')->delete($imagePath);
                    }
                }*/

                if($caracteristica->imagen){
                    // IMAGEB NORMAL
                    $imageUrl = $caracteristica->imagen;
                    // Extraer solo la ruta del archivo desde la URL
                    $imagePath = parse_url($imageUrl, PHP_URL_PATH);
                    $imagePath = str_replace('storage/', '', $imagePath);

                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }

                    // IMG THUMS
                    $imageUrlThum = $caracteristica->img_thumb;
                    // Extraer solo la ruta del archivo desde la URL
                    $imagePathThum = parse_url($imageUrlThum, PHP_URL_PATH);
                    $imagePathThum = str_replace('storage/', '', $imagePathThum);

                    if (Storage::disk('public')->exists($imagePathThum)) {
                        Storage::disk('public')->delete($imagePathThum);
                    }
                }


                $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen')->getClientOriginalExtension();
                //$imageStorage = $request->file('imagen')->storeAs('productos', $file_name);

                $imageStorage = Storage::putFileAs('caracteristicas', $request->imagen ,$file_name, [
                    'visibility' => 'public',
                ]);

                $imageStorageThumb = Storage::putFileAs('caracteristicas/thumbs', $request->imagen, $file_name, [
                    'visibility' => 'public',
                ]);

                // IMAGEN NORMAL
                $manager = new ImageManager(new Driver());
                $img = $manager->read('storage/'.$imageStorage);
                $img->save('storage/'.$imageStorage, 90, 'jpg' ); // Ruta de la imagen, Calidad de imagen, trabajara la imagen como jpg pero no la cambiara de extencion

                // IMAGEN THUMB
                $imgThumb = $manager->read('storage/'.$imageStorageThumb);
                $imgThumb->scale(null, 210, function($constraint){
                    $constraint->aspectRatio();
                }); // Redimenciona el ancho, alto, ajuste de aspecto (máximo)
                $imgThumb->save('storage/'.$imageStorageThumb, 90, 'jpg' );

                $caracteristica->imagen = $imageStorage;
                $caracteristica->img_thumb = $imageStorageThumb;
            }
            $caracteristica->save();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La característica se actualizó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.producto.caracteristica.index');
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

    public function destroy($id)
    {
        try {

            /*$debe = 0;

            $montoDeuda = Ventas::select(
            'ventas.Id_Cliente',
            DB::raw(' (SUM(ventas.Total) - SUM(ventas.MontoPagado)) as debe ' ) )
            ->where('ventas.TipoVenta', 'Crédito')
            ->where('ventas.EstatusVenta','=','Activo')
            ->where('ventas.Id_Cliente', '=', $id)
            ->whereRaw('ventas.MontoPagado < ventas.Total')
            ->groupBy('ventas.Id_Cliente')
            ->get();

            foreach ($montoDeuda as $row) {
                $debe = $row->debe;
            }

            if($debe > 0){
                return redirect()->back()->with('fail','Error elimina cliente');
            }else if($debe <= 0  ){
                $cliente = Clientes::findorfail($id);*/

            if($id > 4){
                $caracteristica = ProductoCaracteristica::findorfail($id);
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

                $caracteristica->update([
                    'nombre' => $caracteristica->nombre.'-'.substr(str_shuffle($permitted_chars), 0, 5),
                    'activo' => 0
                ]);
            }else{
                return redirect()->route('admin.producto.caracteristica.index');
            }
            //}

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La característica se eliminó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            //return redirect()->route('admin.users.index');
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

    public function caracteristica_index_ajax(Request $request)
    {

        if ($request->origen == 'caracteristica.index') {

            $caracteristica = ProductoCaracteristica::where('id', '>', 4)->get()
                ->map(function ($item) {

                // --- Etiqueta de Matriz ---
                $es_activo = $item->activo == 1
                    ? '<span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">Activo</span>'
                    : '<span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded">Eliminado</span>';

                return [
                    'id'        => $item->id,
                    'image'     => '<img class="h-auto max-w-xs object-cover object-center" src="'.asset($item->image).'" alt="Imagen">',
                    'nombre'    => $item->nombre,
                    'tipo'      => $item->tipo,
                    'es_activo' => $es_activo,
                    'acciones'  => e(view('producto_caracteristica.partials.acciones', compact('item'))->render()),
                ];
            });

            return response()->json([ 'data' => $caracteristica ]);
        }
    }
}
