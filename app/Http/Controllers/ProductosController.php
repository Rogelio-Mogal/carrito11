<?php

namespace App\Http\Controllers;

use App\Models\Atributo;
use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\Producto;
use App\Models\ProductoCaracteristica;
use App\Models\ProductoCodigoAlterno;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        //$productoServicio = Producto::where('id', '!=', 1)
        //    ->with(['marca_c', 'familia_c', 'subFamilia_c'])
        //    ->get();
        //return view('producto-servicio.index', compact('productoServicio'));

        return view('producto-servicio.index');
    }

    public function create()
    {
        $productoServicio = new Producto();
        $tipoValues = ['PRODUCTO', 'SERVICIO'];
        $marcaValues = ProductoCaracteristica::where('tipo', 'MARCA')
            ->where('activo', 1)
            ->where('id', '>', 2)
            ->select('id', 'nombre')
            ->get();
        $familiaValues = ProductoCaracteristica::where('tipo', 'FAMILIA')
            ->where('activo', 1)
            ->where('id', '>', 2)
            ->select('id', 'nombre')
            ->get();
        $subfamiliaValues = ProductoCaracteristica::where('tipo', 'SUB_FAMILIA')
            ->where('activo', 1)
            ->select('id', 'nombre')
            ->get();
        $atributosValores = [];

        $metodo = 'create';
        return view('producto-servicio.create', compact('metodo', 'productoServicio', 'tipoValues', 'marcaValues', 'familiaValues', 'subfamiliaValues', 'atributosValores'));
    }

    public function store(Request $request)
    {

        $rules = [
            'nombre' => 'required|string|min:2|max:255|unique:productos',
            'tipo' => 'required|in:PRODUCTO,SERVICIO',
            'codigo_barra' => 'required|string|max:255|unique:productos',
            'marca' => 'required|integer|min:1',
            'familia' => 'required|integer|min:1',
            'sub_familia' => 'nullable|integer',
            'cantidad_minima' => 'required|integer|min:1',
            'garantia' => 'nullable|string|max:255',
            'serie' => 'required|boolean',
            'cantidad' => 'nullable|integer|min:1',
            'precio_costo' => 'nullable|numeric|min:0',
            'imagen_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'imagen_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'imagen_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'descripcion' => 'required|string|min:2|max:1500',
        ];

        // Si es SERVICIO, los precios son requeridos
        if ($request->tipo === 'SERVICIO') {
            $rules['servicio.precio_publico'] = 'required|numeric|min:1';
            $rules['servicio.precio_medio_mayoreo'] = 'required|numeric|min:1';
            $rules['servicio.precio_mayoreo'] = 'required|numeric|min:1';
        }

        // Si es PRODUCTO y se inicializa inventario
        if ($request->input('menuVisible') == 1) {
            $rules['cantidad'] = 'required|numeric|min:1';
            $rules['precio_costo'] = 'required|numeric|min:1';
            $rules['producto.precio_publico'] = 'required|numeric|min:1';
            $rules['producto.precio_medio_mayoreo'] = 'required|numeric|min:1';
            $rules['producto.precio_mayoreo'] = 'required|numeric|min:1';
        }

        $validatedData = $request->validate($rules);
        // dd('as');
        // PRODUCTO/SERVICIOS SIN INVENTARIO INICIAL
        try {

            DB::beginTransaction();

            //valida códigos de barra
            if (Producto::where('codigo_barra', $request->codigo_barra)->exists()) {
                return back()->withErrors([
                    'codigo_barra' => 'El código de barra principal ya existe en otro producto.'
                ])->withInput();
            }


            // 🔹 Validar códigos alternos
            $codigosAlternos = array_filter(array_map('trim', $request->codigos_alternos ?? []));
            if (!empty($codigosAlternos)) {
                $codigosDuplicados = ProductoCodigoAlterno::whereIn('codigo_barra', $codigosAlternos)->pluck('codigo_barra');
                if ($codigosDuplicados->count()) {
                    return back()->withErrors([
                        'codigos_alternos' => 'Los siguientes códigos alternos ya existen en otros productos: ' . $codigosDuplicados->implode(', ')
                    ])->withInput();
                }
            }


            //if ($request->menuVisible == 0) {
            $productoServicio = new Producto();
            $productoServicio->tipo = $request->tipo;
            $productoServicio->nombre = $request->nombre;
            $productoServicio->codigo_barra = $request->codigo_barra;
            $productoServicio->marca = $request->marca;
            $productoServicio->familia = $request->familia;
            $productoServicio->sub_familia = $request->sub_familia;
            $productoServicio->cantidad_minima = $request->cantidad_minima;
            $productoServicio->descripcion = $request->descripcion;
            $productoServicio->garantia = $request->garantia;
            $productoServicio->serie = $request->serie;
            $productoServicio->wci = auth()->user()->id;

            // 🔹 Asignar precios dependiendo del tipo
            if ($request->tipo === 'SERVICIO') {
                $productoServicio->precio_publico = $request->input('servicio.precio_publico');
                $productoServicio->precio_medio_mayoreo = $request->input('servicio.precio_medio_mayoreo');
                $productoServicio->precio_mayoreo = $request->input('servicio.precio_mayoreo');
            }

            if ($request->tipo === 'PRODUCTO') {
                $productoServicio->precio_publico = 0;
                $productoServicio->precio_medio_mayoreo = 0;
                $productoServicio->precio_mayoreo = 0;
            }



            // CÓDIGO PARA EL DOMINIO
            /*if ($request->file('imagen_1')) {
                //$slug = Str::slug($request->nombre);
                //        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                //        $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_1')->getClientOriginalExtension();
                        //$imageStorage = Storage::disk('s3')->putFileAs('productos', $request->imagen_1, $file_name);
                //        $imageStorage = Storage::putFileAs('productos', $request->imagen_1 ,$file_name, [
                //            'visibility' => 'public',
                //        ]);
                //        $productoServicio->imagen_1 = $imageStorage;

                $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $file_name = $slug . '-' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen_1')->getClientOriginalExtension();
                $imageStorage = Storage::putFileAs('productos', $request->imagen_1, $file_name, [
                    'visibility' => 'public',
                ]);

                $imageStorageThumb = Storage::putFileAs('productos/thumbs', $request->imagen_1, $file_name, [
                    'visibility' => 'public',
                ]);

                // IMAGEN NORMAL
                $manager = new ImageManager(new Driver());
                $img = $manager->read('storage/' . $imageStorage);
                $img->save('storage/' . $imageStorage, 90, 'jpg'); // Ruta de la imagen, Calidad de imagen, trabajara la imagen como jpg pero no la cambiara de extencion

                // IMAGEN THUMB
                $imgThumb = $manager->read('storage/' . $imageStorageThumb);
                $imgThumb->scale(null, 210, function ($constraint) {
                    $constraint->aspectRatio();
                }); // Redimenciona el ancho, alto, ajuste de aspecto (máximo)
                $imgThumb->save('storage/' . $imageStorageThumb, 90, 'jpg');

                $productoServicio->imagen_1 = $imageStorage;
                $productoServicio->img_thumb = $imageStorageThumb;
            }
            */

            // CÓDIGO PARA LOCAL
            if ($request->file('imagen_1')) {

                $slug = Str::random(10);
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $file_name = $slug . '-' . substr(str_shuffle($permitted_chars), 0, 3)
                            . '.' . $request->file('imagen_1')->getClientOriginalExtension();

                // Guarda en storage/app/public/productos
                $imageStorage = Storage::disk('public')->putFileAs('productos', $request->imagen_1, $file_name);

                // Guarda thumb
                $imageStorageThumb = Storage::disk('public')->putFileAs('productos/thumbs', $request->imagen_1, $file_name);

                // RUTAS REALES DONDE EXISTEN LOS ARCHIVOS
                $realPath      = storage_path('app/public/' . $imageStorage);
                $realThumbPath = storage_path('app/public/' . $imageStorageThumb);

                // Manager
                $manager = new ImageManager(new Driver());

                // IMAGEN NORMAL
                $img = $manager->read($realPath);
                $img->save($realPath, 90, 'jpg');

                // IMAGEN THUMB
                $imgThumb = $manager->read($realThumbPath);
                $imgThumb->scale(null, 210, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $imgThumb->save($realThumbPath, 90, 'jpg');

                $productoServicio->imagen_1 = $imageStorage;
                $productoServicio->img_thumb = $imageStorageThumb;
            }




            if ($request->file('imagen_2')) {
                /*$slug = Str::slug($request->nombre);
                        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_2')->getClientOriginalExtension();
                        //$imageStorage = Storage::disk('s3')->putFileAs('productos', $request->imagen_2, $file_name);
                        $imageStorage = Storage::putFileAs('productos', $request->imagen_2 ,$file_name, [
                            'visibility' => 'public',
                        ]);
                        $productoServicio->imagen_2 = $imageStorage;*/

                $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $file_name = $slug . '-' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen_2')->getClientOriginalExtension();
                $imageStorage = Storage::putFileAs('productos', $request->imagen_2, $file_name, [
                    'visibility' => 'public',
                ]);

                // IMAGEN NORMAL
                $manager = new ImageManager(new Driver());
                $img = $manager->read('storage/' . $imageStorage);
                $img->save('storage/' . $imageStorage, 90, 'jpg');

                $productoServicio->imagen_2 = $imageStorage;
            }

            if ($request->file('imagen_3')) {
                /* $slug = Str::slug($request->nombre);
                        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_3')->getClientOriginalExtension();
                        //$imageStorage = Storage::disk('s3')->putFileAs('productos', $request->imagen_3, $file_name);
                        $imageStorage = Storage::putFileAs('productos', $request->imagen_3 ,$file_name, [
                            'visibility' => 'public',
                        ]);
                        $productoServicio->imagen_3 = $imageStorage;*/

                $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $file_name = $slug . '-' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen_3')->getClientOriginalExtension();
                $imageStorage = Storage::putFileAs('productos', $request->imagen_3, $file_name, [
                    'visibility' => 'public',
                ]);

                // IMAGEN NORMAL
                $manager = new ImageManager(new Driver());
                $img = $manager->read('storage/' . $imageStorage);
                $img->save('storage/' . $imageStorage, 90, 'jpg');

                $productoServicio->imagen_3 = $imageStorage;
            }

            $productoServicio->save();

            // INSERTAR CÓDIGOS ALTERNOS
            if ($request->filled('codigos_alternos')) {
                foreach ($request->codigos_alternos as $codigo) {
                    $codigo = trim($codigo);
                    if ($codigo !== '') {
                        $productoServicio->codigosAlternos()->create([
                            'codigo_barra' => $codigo,
                            'wci' => auth()->user()->id
                        ]);
                    }
                }
            }


            // INSERTA ATRIBUTOS:
            if ($request->has('atributos')) {
                // Primero eliminamos valores existentes del producto para no duplicar
                $productoServicio->atributos()->detach();

                foreach ($request->atributos as $atributo_id => $item) {
                    if (!empty($item['valor'])) {
                        $valores = is_array($item['valor']) ? $item['valor'] : [$item['valor']];

                        foreach ($valores as $valor) {
                            $productoServicio->atributos()->attach($item['atributo_id'], [
                                'valor' => $valor
                            ]);
                        }
                    }
                }
            }

            // Inventario y kardex si es producto con inventario inicial
            if ($request->tipo === 'PRODUCTO' && $request->menuVisible == 1) {
                $inventario = new Inventario();
                $inventario->sucursal_id = auth()->user()->sucursal_id;
                $inventario->producto_id = $productoServicio->id;
                $inventario->cantidad = $request->cantidad;
                $inventario->precio_costo = $request->precio_costo;
                $inventario->precio_anterior = $request->precio_costo;
                $inventario->precio_publico = $request->input('producto.precio_publico') ?? 0;
                $inventario->precio_medio_mayoreo = $request->input('producto.precio_medio_mayoreo') ?? 0;
                $inventario->precio_mayoreo = $request->input('producto.precio_mayoreo') ?? 0;
                $inventario->save();

                $kardex = new Kardex();
                $kardex->sucursal_id = auth()->user()->sucursal_id;
                $kardex->producto_id = $productoServicio->id;
                $kardex->movimiento_id = 0;
                $kardex->tipo_movimiento = 'ENTRADA';
                $kardex->tipo_detalle = 'INVENTARIO';
                $kardex->fecha = now();
                $kardex->folio = 'S/N';
                $kardex->descripcion = 'Producto con inventario inicial';
                $kardex->debe = $inventario->cantidad;
                $kardex->haber = 0;
                $kardex->saldo = $inventario->cantidad;
                $kardex->wci = auth()->user()->id;
                $kardex->save();
            }

            DB::commit();
            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El producto/servicio se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);
            return redirect()->route('admin.producto.servicio.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $query = $e->getMessage();
            dd($query);
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

    public function store1(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|min:2|max:255|unique:productos',
            'tipo' => 'required|in:PRODUCTO,SERVICIO',
            'codigo_barra' => 'required|string|max:255|unique:productos',
            'marca' => 'required|integer|min:1',
            'familia' => 'required|integer|min:1',
            'sub_familia' => 'nullable|integer',
            'cantidad_minima' => 'required|integer|min:1',
            'garantia' => 'nullable|string|max:255',
            'serie' => 'required|boolean',
            'cantidad' => 'nullable|integer|min:1',
            'precio_costo' => 'nullable|numeric|min:0',
            'imagen_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imagen_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imagen_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'descripcion' => 'required|string|min:2|max:1500',
        ];

        // Si es SERVICIO, los precios son requeridos
        if ($request->tipo === 'SERVICIO') {
            $rules['servicio.precio_publico'] = 'required|numeric|min:1';
            $rules['servicio.precio_medio_mayoreo'] = 'required|numeric|min:1';
            $rules['servicio.precio_mayoreo'] = 'required|numeric|min:1';
        }

        // Si es PRODUCTO y se inicializa inventario
        if ($request->input('menuVisible') == 1) {
            $rules['cantidad'] = 'required|numeric|min:1';
            $rules['precio_costo'] = 'required|numeric|min:1';
            $rules['producto.precio_publico'] = 'required|numeric|min:1';
            $rules['producto.precio_medio_mayoreo'] = 'required|numeric|min:1';
            $rules['producto.precio_mayoreo'] = 'required|numeric|min:1';
        }

        $validatedData = $request->validate($rules);

        // PRODUCTO/SERVICIOS SIN INVENTARIO INICIAL
        try {

            DB::beginTransaction();
            if ($request->menuVisible == 0) {
                $productoServicio = new Producto();
                $productoServicio->tipo = $request->tipo;
                $productoServicio->nombre = $request->nombre;
                $productoServicio->codigo_barra = $request->codigo_barra;
                $productoServicio->marca = $request->marca;
                $productoServicio->familia = $request->familia;
                $productoServicio->sub_familia = $request->sub_familia;
                $productoServicio->cantidad_minima = $request->cantidad_minima;
                $productoServicio->descripcion = $request->descripcion;
                $productoServicio->garantia = $request->garantia;
                $productoServicio->serie = $request->serie;
                $productoServicio->wci = auth()->user()->id;

                // 🔹 Asignar precios dependiendo del tipo
                if ($request->tipo === 'SERVICIO') {
                    $productoServicio->precio_publico = $request->input('servicio.precio_publico');
                    $productoServicio->precio_medio_mayoreo = $request->input('servicio.precio_medio_mayoreo');
                    $productoServicio->precio_mayoreo = $request->input('servicio.precio_mayoreo');
                }

                if ($request->tipo === 'PRODUCTO') {
                    $productoServicio->precio_publico = 0;
                    $productoServicio->precio_medio_mayoreo = 0;
                    $productoServicio->precio_mayoreo = 0;
                }


                if ($request->file('imagen_1')) {
                    /*$slug = Str::slug($request->nombre);
                        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_1')->getClientOriginalExtension();
                        //$imageStorage = Storage::disk('s3')->putFileAs('productos', $request->imagen_1, $file_name);
                        $imageStorage = Storage::putFileAs('productos', $request->imagen_1 ,$file_name, [
                            'visibility' => 'public',
                        ]);
                        $productoServicio->imagen_1 = $imageStorage;*/

                    $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug . '-' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen_1')->getClientOriginalExtension();
                    $imageStorage = Storage::putFileAs('productos', $request->imagen_1, $file_name, [
                        'visibility' => 'public',
                    ]);

                    $imageStorageThumb = Storage::putFileAs('productos/thumbs', $request->imagen_1, $file_name, [
                        'visibility' => 'public',
                    ]);

                    // IMAGEN NORMAL
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read('storage/' . $imageStorage);
                    $img->save('storage/' . $imageStorage, 90, 'jpg'); // Ruta de la imagen, Calidad de imagen, trabajara la imagen como jpg pero no la cambiara de extencion

                    // IMAGEN THUMB
                    $imgThumb = $manager->read('storage/' . $imageStorageThumb);
                    $imgThumb->scale(null, 210, function ($constraint) {
                        $constraint->aspectRatio();
                    }); // Redimenciona el ancho, alto, ajuste de aspecto (máximo)
                    $imgThumb->save('storage/' . $imageStorageThumb, 90, 'jpg');

                    $productoServicio->imagen_1 = $imageStorage;
                    $productoServicio->img_thumb = $imageStorageThumb;
                }

                if ($request->file('imagen_2')) {
                    /*$slug = Str::slug($request->nombre);
                        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_2')->getClientOriginalExtension();
                        //$imageStorage = Storage::disk('s3')->putFileAs('productos', $request->imagen_2, $file_name);
                        $imageStorage = Storage::putFileAs('productos', $request->imagen_2 ,$file_name, [
                            'visibility' => 'public',
                        ]);
                        $productoServicio->imagen_2 = $imageStorage;*/

                    $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug . '-' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen_2')->getClientOriginalExtension();
                    $imageStorage = Storage::putFileAs('productos', $request->imagen_2, $file_name, [
                        'visibility' => 'public',
                    ]);

                    // IMAGEN NORMAL
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read('storage/' . $imageStorage);
                    $img->save('storage/' . $imageStorage, 90, 'jpg');

                    $productoServicio->imagen_2 = $imageStorage;
                }

                if ($request->file('imagen_3')) {
                    /* $slug = Str::slug($request->nombre);
                        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_3')->getClientOriginalExtension();
                        //$imageStorage = Storage::disk('s3')->putFileAs('productos', $request->imagen_3, $file_name);
                        $imageStorage = Storage::putFileAs('productos', $request->imagen_3 ,$file_name, [
                            'visibility' => 'public',
                        ]);
                        $productoServicio->imagen_3 = $imageStorage;*/

                    $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug . '-' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen_3')->getClientOriginalExtension();
                    $imageStorage = Storage::putFileAs('productos', $request->imagen_3, $file_name, [
                        'visibility' => 'public',
                    ]);

                    // IMAGEN NORMAL
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read('storage/' . $imageStorage);
                    $img->save('storage/' . $imageStorage, 90, 'jpg');

                    $productoServicio->imagen_3 = $imageStorage;
                }

                $productoServicio->save();

                // INSERTA ATRIBUTOS:
                if ($request->has('atributos')) {
                    // Primero eliminamos valores existentes del producto para no duplicar
                    $productoServicio->atributos()->detach();

                    foreach ($request->atributos as $atributo_id => $item) {
                        if (!empty($item['valor'])) {
                            $valores = is_array($item['valor']) ? $item['valor'] : [$item['valor']];

                            foreach ($valores as $valor) {
                                $productoServicio->atributos()->attach($item['atributo_id'], [
                                    'valor' => $valor
                                ]);
                            }
                        }
                    }
                }

                DB::commit();
                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El producto/servicio se creó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);
                return redirect()->route('admin.producto.servicio.index');
            }
        } catch (\Exception $e) {
            DB::rollBack();
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

        // PRODUCTO/SERVICIOS CON INVENTARIO INICIAL
        try {
            DB::beginTransaction();
            if ($request->menuVisible == 1 && $request->tipo == 'PRODUCTO') {
                // CREAMOS EL PRODUCTO
                $productoServicio = new Producto();
                $productoServicio->tipo = $request->tipo;
                $productoServicio->nombre = $request->nombre;
                $productoServicio->codigo_barra = $request->codigo_barra;
                $productoServicio->marca = $request->marca;
                $productoServicio->familia = $request->familia;
                $productoServicio->sub_familia = $request->sub_familia;
                $productoServicio->cantidad_minima = $request->cantidad_minima;
                $productoServicio->descripcion = $request->descripcion;
                $productoServicio->garantia = $request->garantia;
                $productoServicio->serie = $request->serie;
                $productoServicio->wci = auth()->user()->id;


                // 🔹 Asignar precios dependiendo del tipo
                if ($request->tipo === 'SERVICIO') {
                    $productoServicio->precio_publico = 0;
                    $productoServicio->precio_medio_mayoreo = 0;
                    $productoServicio->precio_mayoreo = 0;
                }

                if ($request->tipo === 'PRODUCTO') {
                    $productoServicio->precio_publico = 0;
                    $productoServicio->precio_medio_mayoreo = 0;
                    $productoServicio->precio_mayoreo = 0;
                }

                if ($request->file('imagen_1')) {
                    /*$slug = Str::slug($request->nombre);
                        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_1')->getClientOriginalExtension();
                        //$imageStorage = Storage::disk('s3')->putFileAs('productos', $request->imagen_1, $file_name);
                        $imageStorage = Storage::putFileAs('productos', $request->imagen_1 ,$file_name, [
                            'visibility' => 'public',
                        ]);
                        $productoServicio->imagen_1 = $imageStorage;*/

                    $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug . '-' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen_1')->getClientOriginalExtension();
                    $imageStorage = Storage::putFileAs('productos', $request->imagen_1, $file_name, [
                        'visibility' => 'public',
                    ]);

                    $imageStorageThumb = Storage::putFileAs('productos/thumbs', $request->imagen_1, $file_name, [
                        'visibility' => 'public',
                    ]);

                    // IMAGEN NORMAL
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read('storage/' . $imageStorage);
                    $img->save('storage/' . $imageStorage, 90, 'jpg'); // Ruta de la imagen, Calidad de imagen, trabajara la imagen como jpg pero no la cambiara de extencion

                    // IMAGEN THUMB
                    $imgThumb = $manager->read('storage/' . $imageStorageThumb);
                    $imgThumb->scale(null, 210, function ($constraint) {
                        $constraint->aspectRatio();
                    }); // Redimenciona el ancho, alto, ajuste de aspecto (máximo)
                    $imgThumb->save('storage/' . $imageStorageThumb, 90, 'jpg');

                    $productoServicio->imagen_1 = $imageStorage;
                    $productoServicio->img_thumb = $imageStorageThumb;
                }

                if ($request->file('imagen_2')) {
                    /*$slug = Str::slug($request->nombre);
                        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_2')->getClientOriginalExtension();
                        //$imageStorage = Storage::disk('s3')->putFileAs('productos', $request->imagen_2, $file_name);
                        $imageStorage = Storage::putFileAs('productos', $request->imagen_2 ,$file_name, [
                            'visibility' => 'public',
                        ]);
                        $productoServicio->imagen_2 = $imageStorage;*/

                    $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug . '-' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen_2')->getClientOriginalExtension();
                    $imageStorage = Storage::putFileAs('productos', $request->imagen_2, $file_name, [
                        'visibility' => 'public',
                    ]);

                    // IMAGEN NORMAL
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read('storage/' . $imageStorage);
                    $img->save('storage/' . $imageStorage, 90, 'jpg');

                    $productoServicio->imagen_2 = $imageStorage;
                }

                if ($request->file('imagen_3')) {
                    /* $slug = Str::slug($request->nombre);
                        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_3')->getClientOriginalExtension();
                        //$imageStorage = Storage::disk('s3')->putFileAs('productos', $request->imagen_3, $file_name);
                        $imageStorage = Storage::putFileAs('productos', $request->imagen_3 ,$file_name, [
                            'visibility' => 'public',
                        ]);
                        $productoServicio->imagen_3 = $imageStorage;*/

                    $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug . '-' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen_3')->getClientOriginalExtension();
                    $imageStorage = Storage::putFileAs('productos', $request->imagen_3, $file_name, [
                        'visibility' => 'public',
                    ]);

                    // IMAGEN NORMAL
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read('storage/' . $imageStorage);
                    $img->save('storage/' . $imageStorage, 90, 'jpg');

                    $productoServicio->imagen_3 = $imageStorage;
                }

                $productoServicio->save();

                // INSERTA ATRIBUTOS:
                if ($request->has('atributos')) {
                    // Primero eliminamos valores existentes del producto para no duplicar
                    $productoServicio->atributos()->detach();

                    foreach ($request->atributos as $atributo_id => $item) {
                        if (!empty($item['valor'])) {
                            $valores = is_array($item['valor']) ? $item['valor'] : [$item['valor']];

                            foreach ($valores as $valor) {
                                $productoServicio->atributos()->attach($item['atributo_id'], [
                                    'valor' => $valor
                                ]);
                            }
                        }
                    }
                }

                // Obtener el ID del registro recién insertado
                $insertedId = $productoServicio->id;

                // SE SUPONE QUE EL NOMBRE Y EL CODIGO SON UNICOS POR LO TANTO SE INSERTA EN INVENTARIO
                $inventario = new Inventario();
                $inventario->sucursal_id = auth()->user()->sucursal_id;
                $inventario->producto_id = $insertedId;
                $inventario->cantidad = $request->cantidad;
                //$inventario->cantidad_minima = $request->cantidad_minima;
                $inventario->precio_costo = $request->precio_costo;
                $inventario->precio_anterior = $request->precio_costo;
                $inventario->precio_publico = $request->input('producto.precio_publico'); // $request->precio_publico;
                $inventario->precio_medio_mayoreo = $request->input('producto.precio_medio_mayoreo'); //$request->precio_medio_mayoreo;
                $inventario->precio_mayoreo = $request->input('producto.precio_mayoreo'); //$request->precio_mayoreo;
                $inventario->save();





                // INSERTAMOS EL MOVIMIENTO EN EL KARDEX
                $kardex = new Kardex();
                $kardex->sucursal_id = auth()->user()->sucursal_id;
                $kardex->producto_id = $insertedId;
                $kardex->movimiento_id = 0;
                $kardex->tipo_movimiento = 'ENTRADA';
                $kardex->tipo_detalle = 'INVENTARIO';
                $kardex->fecha = Carbon::now();
                $kardex->folio = 'S/N';
                $kardex->descripcion = 'Producto con inventario inicial';
                $kardex->debe = $inventario->cantidad;
                $kardex->haber = 0;
                $kardex->saldo = $inventario->cantidad;
                $kardex->wci = auth()->user()->id;
                $kardex->save();

                //dd($kardex);

                DB::commit();
                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El producto/servicio se creó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);

                return redirect()->route('admin.producto.servicio.index');
            }
        } catch (\Exception $e) {
            DB::rollback();
            $query = $e->getMessage();
            session()->flash('swal', [
                'icon' => "error",
                'title' => "Operación fallida.",
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

    public function show($id)
    {
        /*$productos = Producto::where('id', '!=', 1)
            ->whereHas('inventario', function ($query) use ($id) {
                $query->where('productos_id', $id);
            })
            ->get();*/

        /* $productos = Producto::where('id', '!=', 1)
        ->whereHas('inventario', function ($query) use ($id) {
            $query->where('producto_id', $id);
        })
        ->with('inventario') // Carga la relación 'inventario'
        ->get();*/

        $productos = Producto::where('id', '!=', 1)
            //->whereHas('inventarioUsuario') // solo productos que tengan inventario en la sucursal del usuario
            //->with('inventarioUsuario')
            ->with('inventarioUsuario')     // carga inventario del usuario
            ->get();

        return json_encode($productos);
    }

    public function edit($id)
    {

        $productoServicio = Producto::with('atributos')->findOrFail($id);

        // Obtener valores del pivot en un arreglo [atributo_id => [valores]]
        $atributosValores = [];
        foreach ($productoServicio->atributos as $atributo) {
            $atributosValores[$atributo->pivot->atributo_id][] = $atributo->pivot->valor;
        }

        $tipoValues = ['PRODUCTO', 'SERVICIO'];
        $marcaValues = ProductoCaracteristica::where('tipo', 'MARCA')
            ->where('activo', 1)
            ->where('id', '>', 2)
            ->select('id', 'nombre')
            ->get();
        $familiaValues = ProductoCaracteristica::where('tipo', 'FAMILIA')
            ->where('activo', 1)
            ->where('id', '>', 2)
            ->select('id', 'nombre')
            ->get();
        $subfamiliaValues = ProductoCaracteristica::where('tipo', 'SUB_FAMILIA')
            ->where('activo', 1)
            ->select('id', 'nombre')
            ->get();

        if ($productoServicio->activo == 1 && $productoServicio->id > 1) {
            $metodo = 'edit';
            return view('producto-servicio.edit', compact(
                'productoServicio',
                'metodo',
                'tipoValues',
                'marcaValues',
                'familiaValues',
                'subfamiliaValues',
                'atributosValores'  // <-- Pasamos los valores a la vista
            ));
        } else {
            return redirect()->route('admin.producto.servicio.index');
        }
    }

    public function update(Request $request, $id)
    {
        $productoServicio = Producto::findorfail($id);

        // Verificar si hubo cambios en los datos
        $changes = [
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'marca' => $request->marca,
            'familia' => $request->familia,
            'sub_familia' => $request->sub_familia,
            'cantidad_minima' => $request->cantidad_minima,
            'garantia' => $request->garantia,
            'serie' => $request->serie,
            'imagen_1' => $request->imagen_1,
            'imagen_2' => $request->imagen_1,
            'imagen_3' => $request->imagen_1,
            'descripcion' => $request->imagen_1,
        ];

        // Verificar si los campos de imagen cambiaron
        $imagen1Changed = $request->hasFile('imagen_1') && $request->file('imagen_1')->isValid();
        $imagen2Changed = $request->hasFile('imagen_2') && $request->file('imagen_2')->isValid();
        $imagen3Changed = $request->hasFile('imagen_3') && $request->file('imagen_3')->isValid();

        // Verificar cambios en atributos dinámicos
        $atributosCambiaron = false;
        if ($request->has('atributos')) {
            foreach ($request->atributos as $atributo_id => $item) {
                $valoresRequest = is_array($item['valor']) ? $item['valor'] : [$item['valor']];
                $valoresActuales = $productoServicio->atributos()
                    ->where('atributo_id', $atributo_id)
                    ->pluck('valor')
                    ->toArray();

                // Comparar arrays: si son diferentes, hubo cambios
                if ($valoresRequest !== $valoresActuales) {
                    $atributosCambiaron = true;
                    break;
                }
            }
        }

        //Verifica cambios en códigos de barra alternos
        $codigosAlternosCambiaron = false;
        $codigosExistentes = $productoServicio->codigosAlternos()->pluck('codigo_barra')->toArray();
        $codigosRequest = $request->codigos_alternos ?? [];
        $codigosRequestTrim = array_map('trim', $codigosRequest);
        // Comparar arrays (orden no importa)
        if (array_diff($codigosExistentes, $codigosRequestTrim) || array_diff($codigosRequestTrim, $codigosExistentes)) {
            $codigosAlternosCambiaron = true;
        }


        if (
            $productoServicio->isDirty($changes) || $imagen1Changed || $imagen2Changed || $imagen3Changed || $atributosCambiaron
            || $codigosAlternosCambiaron
        ) {
            $rules = [
                'nombre' => "required|string|min:2|max:255|unique:productos,nombre,{$productoServicio->id}",
                'tipo' => 'required|in:PRODUCTO,SERVICIO',
                'codigo_barra' => "required|string|max:255|unique:productos,codigo_barra,{$productoServicio->id}",
                'marca' => 'required|integer|min:1',
                'familia' => 'required|integer|min:1',
                'sub_familia' => 'nullable|integer',
                'cantidad_minima' => 'required|integer|min:1',
                'garantia' => 'nullable|string|max:255',
                'serie' => 'required|boolean',
                'imagen_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'imagen_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'imagen_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'descripcion' => 'required|string|min:2|max:1500',
            ];

            $validatedData = $request->validate($rules);

            try {
                DB::beginTransaction();

                // Validar que el código principal no exista en otro producto
                if (Producto::where('codigo_barra', $request->codigo_barra)
                    ->where('id', '!=', $productoServicio->id)
                    ->exists()
                ) {
                    return back()->withErrors([
                        'codigo_barra' => 'El código de barra principal ya existe en otro producto.'
                    ])->withInput();
                }

                // Validar códigos alternos
                $codigosAlternos = array_filter(array_map('trim', $request->codigos_alternos ?? []));

                if (!empty($codigosAlternos)) {
                    // Buscar códigos alternos que ya existan en otros productos
                    $codigosDuplicados = ProductoCodigoAlterno::whereIn('codigo_barra', $codigosAlternos)
                        ->where('producto_id', '!=', $productoServicio->id)
                        ->pluck('codigo_barra');

                    if ($codigosDuplicados->count()) {
                        return back()->withErrors([
                            'codigos_alternos' => 'Los siguientes códigos alternos ya existen en otros productos: ' . $codigosDuplicados->implode(', ')
                        ])->withInput();
                    }
                }

                $productoServicio->tipo = $request->tipo;
                $productoServicio->nombre = $request->nombre;
                $productoServicio->codigo_barra = $request->codigo_barra;
                $productoServicio->marca = $request->marca;
                $productoServicio->familia = $request->familia;
                $productoServicio->sub_familia = $request->sub_familia;
                $productoServicio->cantidad_minima = $request->cantidad_minima;
                $productoServicio->descripcion = $request->descripcion;
                $productoServicio->garantia = $request->garantia;
                $productoServicio->serie = $request->serie;

                if ($request->file('imagen_1')) {
                    /*
                    $slug = Str::slug($request->nombre);
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_1')->getClientOriginalExtension();
                    //$imageStorage = Storage::disk('s3')->putFileAs('productos', $request->imagen_1, $file_name);
                    $imageStorage = Storage::putFileAs('productos', $request->imagen_1 ,$file_name, [
                        'visibility' => 'public',
                    ]);
                    $productoServicio->imagen_1 = $imageStorage;
                    */

                    if ($productoServicio->imagen_1) {
                        // IMAGEB NORMAL
                        $imageUrl = $productoServicio->imagen_1;
                        // Extraer solo la ruta del archivo desde la URL
                        $imagePath = parse_url($imageUrl, PHP_URL_PATH);
                        $imagePath = str_replace('storage/', '', $imagePath);

                        if (Storage::disk('public')->exists($imagePath)) {
                            Storage::disk('public')->delete($imagePath);
                        }

                        // IMG THUMS
                        $imageUrlThum = $productoServicio->img_thumb;
                        // Extraer solo la ruta del archivo desde la URL
                        $imagePathThum = parse_url($imageUrlThum, PHP_URL_PATH);
                        $imagePathThum = str_replace('storage/', '', $imagePathThum);

                        if (Storage::disk('public')->exists($imagePathThum)) {
                            Storage::disk('public')->delete($imagePathThum);
                        }
                    }


                    $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug . '-' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen_1')->getClientOriginalExtension();
                    //$imageStorage = $request->file('imagen')->storeAs('productos', $file_name);

                    $imageStorage = Storage::putFileAs('productos', $request->imagen_1, $file_name, [
                        'visibility' => 'public',
                    ]);

                    $imageStorageThumb = Storage::putFileAs('productos/thumbs', $request->imagen_1, $file_name, [
                        'visibility' => 'public',
                    ]);

                    // IMAGEN NORMAL
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read('storage/' . $imageStorage);
                    $img->save('storage/' . $imageStorage, 90, 'jpg'); // Ruta de la imagen, Calidad de imagen, trabajara la imagen como jpg pero no la cambiara de extencion

                    // IMAGEN THUMB
                    $imgThumb = $manager->read('storage/' . $imageStorageThumb);
                    $imgThumb->scale(null, 210, function ($constraint) {
                        $constraint->aspectRatio();
                    }); // Redimenciona el ancho, alto, ajuste de aspecto (máximo)
                    $imgThumb->save('storage/' . $imageStorageThumb, 90, 'jpg');

                    $productoServicio->imagen_1 = $imageStorage;
                    $productoServicio->img_thumb = $imageStorageThumb;
                }

                if ($request->file('imagen_2')) {
                    /*
                    $slug = Str::slug($request->nombre);
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_2')->getClientOriginalExtension();
                    //$imageStorage = Storage::disk('s3')->putFileAs('productos', $request->imagen_2, $file_name);
                    $imageStorage = Storage::putFileAs('productos', $request->imagen_2 ,$file_name, [
                        'visibility' => 'public',
                    ]);
                    $productoServicio->imagen_2 = $imageStorage;
                    */

                    if ($productoServicio->imagen_2) {
                        // IMAGEB NORMAL
                        $imageUrl = $productoServicio->imagen_2;
                        // Extraer solo la ruta del archivo desde la URL
                        $imagePath = parse_url($imageUrl, PHP_URL_PATH);
                        $imagePath = str_replace('storage/', '', $imagePath);

                        if (Storage::disk('public')->exists($imagePath)) {
                            Storage::disk('public')->delete($imagePath);
                        }
                    }

                    $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug . '-' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen_2')->getClientOriginalExtension();
                    //$imageStorage = $request->file('imagen')->storeAs('productos', $file_name);

                    $imageStorage = Storage::putFileAs('productos', $request->imagen_2, $file_name, [
                        'visibility' => 'public',
                    ]);

                    // IMAGEN NORMAL
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read('storage/' . $imageStorage);
                    $img->save('storage/' . $imageStorage, 90, 'jpg'); // Ruta de la imagen, Calidad de imagen, trabajara la imagen como jpg pero no la cambiara de extencion

                    $productoServicio->imagen_2 = $imageStorage;
                }

                if ($request->file('imagen_3')) {
                    /*
                    $slug = Str::slug($request->nombre);
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_3')->getClientOriginalExtension();
                    //$imageStorage = Storage::disk('s3')->putFileAs('productos', $request->imagen_3, $file_name);
                    $imageStorage = Storage::putFileAs('productos', $request->imagen_3 ,$file_name, [
                        'visibility' => 'public',
                    ]);
                    $productoServicio->imagen_3 = $imageStorage;
                    */

                    if ($productoServicio->imagen_3) {
                        // IMAGEB NORMAL
                        $imageUrl = $productoServicio->imagen_3;
                        // Extraer solo la ruta del archivo desde la URL
                        $imagePath = parse_url($imageUrl, PHP_URL_PATH);
                        $imagePath = str_replace('storage/', '', $imagePath);

                        if (Storage::disk('public')->exists($imagePath)) {
                            Storage::disk('public')->delete($imagePath);
                        }
                    }

                    $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug . '-' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen_3')->getClientOriginalExtension();
                    //$imageStorage = $request->file('imagen')->storeAs('productos', $file_name);

                    $imageStorage = Storage::putFileAs('productos', $request->imagen_3, $file_name, [
                        'visibility' => 'public',
                    ]);

                    // IMAGEN NORMAL
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read('storage/' . $imageStorage);
                    $img->save('storage/' . $imageStorage, 90, 'jpg'); // Ruta de la imagen, Calidad de imagen, trabajara la imagen como jpg pero no la cambiara de extencion

                    $productoServicio->imagen_3 = $imageStorage;
                }

                $productoServicio->save();

                // ELIMINAR TODOS LOS CÓDIGOS EXISTENTES
                $productoServicio->codigosAlternos()->delete();

                // INSERTAR NUEVOS CÓDIGOS (si los hay)
                foreach ($codigosAlternos as $codigo) {
                    $productoServicio->codigosAlternos()->create([
                        'codigo_barra' => $codigo,
                        'wci' => auth()->user()->id
                    ]);
                }

                // INSERTA ATRIBUTOS:
                if ($request->has('atributos')) {
                    // Primero eliminamos valores existentes del producto para no duplicar
                    $productoServicio->atributos()->detach();

                    foreach ($request->atributos as $atributo_id => $item) {
                        if (!empty($item['valor'])) {
                            $valores = is_array($item['valor']) ? $item['valor'] : [$item['valor']];

                            foreach ($valores as $valor) {
                                $productoServicio->atributos()->attach($item['atributo_id'], [
                                    'valor' => $valor
                                ]);
                            }
                        }
                    }
                }

                /*
                    // Actualizar el campo cantidad_minima de  inventario
                    $inventario = $productoServicio->inventario;
                    $inventario->cantidad_minima = $request->cantidad_minima;
                    $inventario->save();
                */

                DB::commit();

                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El producto/servicio se actualizó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);

                return redirect()->route('admin.producto.servicio.index');
            } catch (\Exception $e) {
                DB::rollback();
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
        } else {
            session()->flash('swal', [
                'icon' => "info",
                'title' => "Sin cambios",
                'text' => "No se realizaron cambios en el producto o servicio.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.producto.servicio.index');
        }
    }

    public function destroy($id)
    {
        try {
            $productoServicio = Producto::findorfail($id);

            if ($productoServicio->id > 1) {
                if ($productoServicio->activo == 0) {
                    return response()->json([
                        'swal' => [
                            'icon' => "success",
                            'title' => "Operación correcta",
                            'text' => "El producto/servicio se eliminó correctamente.",
                            'customClass' => [
                                'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                            ],
                            'buttonsStyling' => false
                        ],
                        'success' => 'La compra se eliminó correctamente.'
                    ], 200);
                }
                // Verifica si el registro es un producto o un servicio
                if ($productoServicio->tipo == 'PRODUCTO') {
                    // Es un producto, verifica la existencia en el inventario
                    $inventario = $productoServicio->inventario;

                    if ($inventario && $inventario->cantidad > 0) {
                        // Si el producto tiene stock, no permitir la actualización/eliminación
                        return response()->json([
                            'swal' => [
                                'icon' => "error",
                                'title' => "Operación fallida",
                                'text' => "No se puede eliminar el producto: " . $productoServicio->nombre . " porque tiene stock en existencia.",
                                'customClass' => [
                                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                ],
                                'buttonsStyling' => false
                            ],
                            'error' => 'No se puede eliminar el producto: ' . $productoServicio->nombre . ' porque tiene stock en existencia.'
                        ], 400);
                    }
                }

                // Si es un servicio o un producto sin stock, procede con la actualización/eliminación
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $productoServicio->update([
                    'nombre' => $productoServicio->nombre . '-' . substr(str_shuffle($permitted_chars), 0, 5),
                    'codigo_barra' => $productoServicio->codigo_barra . '-' . substr(str_shuffle($permitted_chars), 0, 5),
                    'activo' => 0
                ]);

                // Respuesta exitosa
                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "El producto/servicio se eliminó correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'La compra se eliminó correctamente.'
                ], 200);
            } else {
                return redirect()->route('admin.producto.servicio.index');
            }
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

    public function productos_index_ajax(Request $request)
    {
        // TODOS LOS PRODUCTOS PARA EL INDEX DE LA TABLA PRODUCTOS/SERVICIOS
        if ($request->origen == 'productos.index') {
            $productos = Producto::where('id', '!=', 1)
                //->where('activo', 1)
                ->with(['marca_c', 'familia_c', 'subFamilia_c'])
                ->get();

            return response()->json(['data' => $productos]);
        }

        // PRODUCTOS PARA EL APARTADO DE COMPRAS (SOLO PRODUCTOS)
        if ($request->origen == 'productos.compras') {
            /*$productos = Producto::where('id', '!=', 1)
                ->where('activo', 1)
                ->where('tipo', '=' , 'PRODUCTO')
                ->with(['inventarioUsuario'])
                ->get();*/

            // Obtener productos con inventario (para la sucursal del usuario)
            $productosProductos = Producto::where('activo', 1)
                ->where('tipo', 'PRODUCTO')
                ->where('id', '>', 1)
                ->where('activo', 1)
                //->with(['inventarioUsuario'])
                ->get();

            return response()->json(['data' => $productosProductos]);
        }

        // PRODUCTOS-SERVICIOS PARA VENTAS
        if ($request->origen == 'productos.ventas') {
            /*
            // Obtener productos con inventario (para la sucursal del usuario)
            $productosProductos = Producto::where('activo', 1)
                ->where('tipo', 'PRODUCTO')
                ->where('id', '>', 1)
                ->with(['inventarioUsuario'])
                ->get();

            // Obtener todos los servicios
            $productosServicios = Producto::where('activo', 1)
                ->where('tipo', 'SERVICIO')
                ->get();
            */

            // Productos con inventario
            $productosProductos = Producto::where('activo', 1)
                ->where('tipo', 'PRODUCTO')
                ->where('id', '>', 1)
                ->with(['inventarioUsuario'])
                ->get()
                ->map(function ($item) {
                    // Agregamos un campo inventario estandarizado
                    $item->inventario_data = $item->inventarioUsuario ? $item->inventarioUsuario->toArray() : null;
                    $item->serie = $item->serie ?? 0;
                    return $item;
                });

            // Servicios
            $productosServicios = Producto::where('activo', 1)
                ->where('tipo', 'SERVICIO')
                ->get()
                ->map(function ($item) {
                    // Creamos un inventario virtual para que JS lo use igual que los productos
                    $item->inventario_data = [
                        'id' => 0,
                        'sucursal_id' => auth()->user()->sucursal_id,
                        'producto_id' => $item->id,
                        'serie' => 0, // stock virtual
                        'cantidad' => 500, // stock virtual
                        'precio_costo' => $item->precio_publico ?? 0,
                        'precio_anterior' => $item->precio_publico ?? 0,
                        'precio_publico' => $item->precio_publico ?? 0,
                        'precio_medio_mayoreo' => $item->precio_medio_mayoreo ?? $item->precio_publico ?? 0,
                        'precio_mayoreo' => $item->precio_mayoreo ?? $item->precio_publico ?? 0,
                        'activo' => 1
                    ];
                    return $item;
                });

            // Unir ambos conjuntos
            $productos = $productosProductos->merge($productosServicios);

            return response()->json(['data' => $productos]);
        }

        // PRODUCTOS PARA EL APARTADO DE INVENTARIO (SOLO PRODUCTOS)
        if ($request->origen == 'productos.inventario') {

            $query = Producto::query()
                ->join('inventarios', 'productos.id', '=', 'inventarios.producto_id')
                ->join('sucursales', 'inventarios.sucursal_id', '=', 'sucursales.id')
                ->where('productos.id', '!=', 1)
                ->where('productos.activo', 1)
                ->where('productos.tipo', 'PRODUCTO');

            if ($request->filled('sucursal_id')) {
                $query->where('inventarios.sucursal_id', $request->sucursal_id);
            }

            $productos = $query->select(
                'productos.id as id',
                'productos.img_thumb as img_thumb',
                'productos.nombre as producto_nombre',
                'productos.codigo_barra as codigo_barra',
                'sucursales.id as sucursal_id',
                'sucursales.nombre as sucursal_nombre',
                'inventarios.id as inventario_id',
                'inventarios.cantidad',
                'inventarios.producto_apartado',
                'inventarios.producto_servicio',
                'inventarios.producto_garantia',
                'inventarios.precio_costo',
                'inventarios.precio_anterior',
                'inventarios.precio_publico',
                'inventarios.precio_medio_mayoreo',
                'inventarios.precio_mayoreo',
                'inventarios.activo'
            )
                ->orderBy('productos.nombre')
                ->get()
                ->map(function ($item) {
                    $item->image = $item->img_thumb
                        ? (substr($item->img_thumb, 0, 8) === 'https://'
                            ? $item->img_thumb
                            : Storage::url($item->img_thumb))
                        : 'https://pcserviciostc.com.mx/img/no_disponible.png';
                    return $item;
                });

            return response()->json(['data' => $productos]);
        }

        // PRODUCTOS PARA EL APARTADO DE COTIZACIONES (SOLO PRODUCTOS)
        if ($request->origen == 'productos.cotizaciones') {
            $productos = Producto::where('id', '!=', 1)
            ->where('tipo', 'PRODUCTO')
            ->where('activo', 1)
            ->with(['inventarioUsuario']) // Cargamos la relación
            ->get()
            ->map(function ($producto) {
                $inventario = $producto->inventarioUsuario;

                // Si existe inventario, usamos sus precios; si no, colocamos 0
                $producto->precio_publico = $inventario->precio_publico ?? 0;
                $producto->precio_medio_mayoreo = $inventario->precio_medio_mayoreo ?? 0;
                $producto->precio_mayoreo = $inventario->precio_mayoreo ?? 0;

                return $producto;
            });

            return response()->json(['data' => $productos]);
        }
    }

    public function busca_codbarra_productos_compra(Request $request)
    {
        // OBTENGO LOS PRODUCTOS POR SU CODIGO DE BARRAS (COMPRAS)
        if ($request->origen == 'busca.producto.servicio.compra') {
            $productos = Producto::where('codigo_barra', '=', $request->codbarra)
                ->where('tipo', 'PRODUCTO')
                ->where('activo', 1)
                //->with(['inventario'])
                ->get();

            return response()->json(['data' => $productos]);
        }

        // OBTENGO LOS PRODUCTOS POR SU CODIGO DE BARRAS (COTIZACIONES)
        if ($request->origen == 'busca.producto.servicio.cotizaciones') {
            $productos = Producto::where('codigo_barra', '=', $request->codbarra)
                ->where('tipo', 'PRODUCTO')
                ->with(['inventario'])
                ->get();

            return response()->json(['data' => $productos]);
        }
    }

    public function getAtributosByFamilia($familiaId)
    {
        $atributos = Atributo::whereHas('familias', function ($q) use ($familiaId) {
            $q->where('familia_id', $familiaId);
        })->get();

        return response()->json($atributos);
    }
    public function buscarProductoPorCodigo(Request $request)
    {
        $codigo = trim($request->input('codigo'));
        $sucursalId = auth()->user()->sucursal_id;
        $tipoBusqueda = $request->input('tipo_busqueda', 'ventas');

        if (!$codigo) {
            return response()->json(['error' => 'No se proporcionó código.'], 400);
        }

        // Función para generar la query según tipo de búsqueda
        $generarQuery = function ($tipo) use ($sucursalId) {
            $query = Producto::query()->where('activo', 1)->with('codigosAlternos');

            switch ($tipo) {
                case 'ventas':
                    $query->where('id', '>', 1)
                        ->with(['inventarios' => function ($q) use ($sucursalId) {
                            $q->where('sucursal_id', $sucursalId);
                        }]);
                    break;

                case 'compras':
                    $query->where('tipo', 'PRODUCTO')->where('id', '>', 1);
                    break;

                case 'apartados':
                    $query->where('id', '>', 1) // productos activos
                        ->whereHas('inventarios', function ($q) use ($sucursalId) {
                            $q->where('sucursal_id', $sucursalId)
                                ->where('cantidad', '>', 0);
                        })
                        ->with(['inventarios' => function ($q) use ($sucursalId) {
                            $q->where('sucursal_id', $sucursalId);
                        }]);
                    break;

                case 'garantias':
                    $query->where('tipo', 'PRODUCTO')->where('id', '>', 1);
                    break;
            }

            return $query;
        };

        // Buscar primero por código principal
        $queryProducto = $generarQuery($tipoBusqueda);
        $producto = $queryProducto->where('codigo_barra', $codigo)->first();

        // Si no se encuentra, buscar por código alterno
        if (!$producto) {
            $alterno = ProductoCodigoAlterno::where('codigo_barra', $codigo)->first();
            if ($alterno) {
                $queryAlterno = $generarQuery($tipoBusqueda);
                $producto = $queryAlterno->where('id', $alterno->producto_id)->first();
            }
        }

        if (!$producto) {
            return response()->json(['error' => 'Producto no encontrado.'], 404);
        }

        // Construir inventario_data
        if ($producto->tipo === 'PRODUCTO') {
            $inventario = $producto->inventarios->first();
            $producto->inventario_data = $inventario ? $inventario->toArray() : null;
            $producto->serie = $producto->serie ?? 0;
        } else {
            $producto->inventario_data = [
                'id' => 0,
                'sucursal_id' => $sucursalId,
                'producto_id' => $producto->id,
                'serie' => 0,
                'cantidad' => 500,
                'precio_costo' => $producto->precio_publico ?? 0,
                'precio_anterior' => $producto->precio_publico ?? 0,
                'precio_publico' => $producto->precio_publico ?? 0,
                'precio_medio_mayoreo' => $producto->precio_medio_mayoreo ?? $producto->precio_publico ?? 0,
                'precio_mayoreo' => $producto->precio_mayoreo ?? $producto->precio_publico ?? 0,
                'activo' => 1
            ];
        }

        return response()->json(['data' => [$producto]]);
    }

    public function buscarProductoPorCodigo_no2(Request $request)
    {
        $codigo = trim($request->input('codigo'));
        $sucursalId = auth()->user()->sucursal_id;
        $tipoBusqueda = $request->input('tipo_busqueda', 'ventas');

        if (!$codigo) {
            return response()->json(['error' => 'No se proporcionó código.'], 400);
        }

        // Query base para todos los productos activos
        $queryProducto = Producto::query()->where('activo', 1)->with('codigosAlternos');

        // Aplicar condiciones según tipo de búsqueda
        switch ($tipoBusqueda) {
            case 'ventas':
                $queryProducto->where('id', '>', 1)
                    ->with(['inventarios' => function ($q) use ($sucursalId) {
                        $q->where('sucursal_id', $sucursalId);
                    }]);
                break;

            case 'compras':
                $queryProducto->where('tipo', 'PRODUCTO')->where('id', '>', 1);
                break;

            case 'apartados':
                $queryProducto->whereHas('inventarios', function ($q) use ($sucursalId) {
                    $q->where('sucursal_id', $sucursalId)
                        ->where('cantidad', '>', 0);
                })->with(['inventarios' => function ($q) use ($sucursalId) {
                    $q->where('sucursal_id', $sucursalId);
                }]);
                break;

            case 'garantias':
                $queryProducto->where('tipo', 'PRODUCTO')->where('id', '>', 1);
                break;
        }

        // Buscar primero por código principal
        $producto = (clone $queryProducto)->where('codigo_barra', $codigo)->first();

        // Si no se encuentra, buscar por código alterno
        if (!$producto) {
            $alterno = ProductoCodigoAlterno::where('codigo_barra', $codigo)->first();
            if ($alterno) {
                // Creamos un query base sin filtrar por código, pero respetando filtros de tipoBusqueda
                $queryAlterno = Producto::query()->where('activo', 1)->with('codigosAlternos');

                // Aplicar filtros según tipoBusqueda (igual que arriba)
                switch ($tipoBusqueda) {
                    case 'ventas':
                        $queryAlterno->where('id', '>', 1)
                            ->with(['inventarios' => function ($q) use ($sucursalId) {
                                $q->where('sucursal_id', $sucursalId);
                            }]);
                        break;

                    case 'compras':
                        $queryAlterno->where('tipo', 'PRODUCTO')->where('id', '>', 1);
                        break;

                    case 'apartados':
                        $queryAlterno->whereHas('inventarios', function ($q) use ($sucursalId) {
                            $q->where('sucursal_id', $sucursalId)
                                ->where('cantidad', '>', 0);
                        })->with(['inventarios' => function ($q) use ($sucursalId) {
                            $q->where('sucursal_id', $sucursalId);
                        }]);
                        break;

                    case 'garantias':
                        $queryAlterno->where('tipo', 'PRODUCTO')->where('id', '>', 1);
                        break;
                }

                $producto = $queryAlterno->where('id', $alterno->producto_id)->first();
            }
        }

        if (!$producto) {
            return response()->json(['error' => 'Producto no encontrado.'], 404);
        }

        // Construir inventario_data
        if ($producto->tipo === 'PRODUCTO') {
            $inventario = $producto->inventarios->first();
            $producto->inventario_data = $inventario ? $inventario->toArray() : null;
            $producto->serie = $producto->serie ?? 0;
        } else {
            $producto->inventario_data = [
                'id' => 0,
                'sucursal_id' => $sucursalId,
                'producto_id' => $producto->id,
                'serie' => 0,
                'cantidad' => 500,
                'precio_costo' => $producto->precio_publico ?? 0,
                'precio_anterior' => $producto->precio_publico ?? 0,
                'precio_publico' => $producto->precio_publico ?? 0,
                'precio_medio_mayoreo' => $producto->precio_medio_mayoreo ?? $producto->precio_publico ?? 0,
                'precio_mayoreo' => $producto->precio_mayoreo ?? $producto->precio_publico ?? 0,
                'activo' => 1
            ];
        }

        return response()->json(['data' => [$producto]]);
    }

    public function buscarProductoPorCodigo_NO(Request $request)
    {
        $codigo = trim($request->input('codigo'));
        $sucursalId = auth()->user()->sucursal_id;
        $tipoBusqueda = $request->input('tipo_busqueda', 'ventas');

        if (!$codigo) {
            return response()->json(['error' => 'No se proporcionó código.'], 400);
        }

        // Query base
        $queryProducto = Producto::query()
            ->where('activo', 1)
            ->with('codigosAlternos');

        // Aplicar condiciones según el tipo de búsqueda
        switch ($tipoBusqueda) {
            case 'ventas':
                $queryProducto->where('id', '>', 1)
                    ->with(['inventarios' => function ($q) use ($sucursalId) {
                        $q->where('sucursal_id', $sucursalId);
                    }]);
                break;

            case 'compras':
                // Solo productos (no servicios), activos y con o sin inventario
                $queryProducto->where('tipo', 'PRODUCTO')
                    ->where('id', '>', 1);
                break;

            case 'cotizaciones':
                break;

            case 'apartados':
                $queryProducto->whereHas('inventarios', function ($q) use ($sucursalId) {
                    $q->where('sucursal_id', $sucursalId)
                        ->where('cantidad', '>', 0);
                })->with(['inventarios' => function ($q) use ($sucursalId) {
                    $q->where('sucursal_id', $sucursalId);
                }]);
                break;

            case 'garantias':
                $queryProducto->where('tipo', 'PRODUCTO')
                    ->where('id', '>', 1);
                break;
        }

        // Guardar copia sin filtros adicionales (para alternos)
        $queryBase = clone $queryProducto;

        // Buscar por código principal
        $producto = (clone $queryProducto)
            ->where('codigo_barra', $codigo)
            ->first();

        // Si no se encuentra, buscar en códigos alternos
        if (!$producto) {
            $alterno = ProductoCodigoAlterno::where('codigo_barra', $codigo)->first();

            if ($alterno) {
                // Usar la query base sin el filtro de código_barra
                $producto = (clone $queryBase)
                    ->where('id', $alterno->producto_id)
                    ->first();
            }
        }

        if (!$producto) {
            return response()->json(['error' => 'Producto no encontrado.'], 404);
        }

        // Construir inventario_data
        if ($producto->tipo === 'PRODUCTO') {
            $inventario = $producto->inventarios->first();
            $producto->inventario_data = $inventario ? $inventario->toArray() : null;
            $producto->serie = $producto->serie ?? 0;
        } else {
            $producto->inventario_data = [
                'id' => 0,
                'sucursal_id' => $sucursalId,
                'producto_id' => $producto->id,
                'serie' => 0,
                'cantidad' => 500,
                'precio_costo' => $producto->precio_publico ?? 0,
                'precio_anterior' => $producto->precio_publico ?? 0,
                'precio_publico' => $producto->precio_publico ?? 0,
                'precio_medio_mayoreo' => $producto->precio_medio_mayoreo ?? $producto->precio_publico ?? 0,
                'precio_mayoreo' => $producto->precio_mayoreo ?? $producto->precio_publico ?? 0,
                'activo' => 1
            ];
        }

        return response()->json(['data' => [$producto]]);
    }
}
