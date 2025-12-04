<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Carbon\Carbon;

class ClientesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        //$clientes = Cliente::where('id', '!=', 1)->get();
        //return view('clientes.index', compact('clientes'));

        return view('clientes.index');
    }

    public function create()
    {
        $cliente = new Cliente();
        $metodo = 'create';
        $tipoValues = ['CLIENTE PÚBLICO', 'CLIENTE MEDIO MAYOREO', 'CLIENTE MAYOREO'];
        $ejecutivoValues = User::where('tipo_usuario', 'punto_de_venta')
            ->where('activo', 1)
            ->select('id', 'full_name')
            ->get();

        return view('clientes.create', compact('cliente', 'metodo', 'tipoValues', 'ejecutivoValues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'telefono' => 'required|string|max:255|unique:clientes',
            'direccion' => 'nullable|string|min:2|max:255',
            'email' => 'nullable|email|max:255|unique:clientes',
            'tipo_cliente' => 'required|in:CLIENTE PÚBLICO,CLIENTE MEDIO MAYOREO,CLIENTE MAYOREO',
            'comentario' => 'nullable|string|min:2|max:1500',
            'ejecutivo_id' => 'nullable|integer',

            'dias_credito'   => 'nullable|integer|min:0',
            'limite_credito' => 'nullable|numeric|min:0',

        ]);

        // Validación personalizada para full_name
        $fullName = $request->name . ' ' . $request->last_name;
        if (Cliente::where('full_name', $fullName)->exists()) {
            return back()->withErrors(['full_name' => 'El cliente ya se encuentra registrado.'])->withInput();
        }

        try {
            $cliente = new Cliente();
            $cliente->name = $request->name;
            $cliente->last_name = $request->last_name;
            $cliente->full_name = $fullName;
            $cliente->telefono = $request->telefono;
            $cliente->direccion = $request->direccion;
            $cliente->email = $request->email;
            $cliente->tipo_cliente = $request->tipo_cliente;
            $cliente->comentario = $request->comentario;
            $cliente->ejecutivo_id = $request->ejecutivo_id;
            $cliente->dias_credito = $request->dias_credito;
            $cliente->limite_credito = $request->limite_credito;
            $cliente->wci = auth()->user()->id;
            $cliente->save();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El cliente se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.clientes.index');
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => "error",
                'title' => "Operación fallida",
                'text' => "Hubo un error durante el proceso, por favor intente más tarde.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
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
        $cliente = Cliente::findorfail($id);
        if ($cliente->activo == 1 && $cliente->id > 1) {
            $metodo = 'edit';
            $tipoValues = ['CLIENTE PÚBLICO', 'CLIENTE MEDIO MAYOREO', 'CLIENTE MAYOREO'];
            $ejecutivoValues = User::where('tipo_usuario', 'punto_de_venta')
                ->where('activo', 1)
                ->select('id', 'full_name')
                ->get();
            return view('clientes.edit', compact('cliente', 'metodo', 'tipoValues', 'ejecutivoValues'));
        } else {
            return redirect()->route('admin.clientes.index');
        }
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findorfail($id);
        // ACTUALIZAMOS EL REGISTRO
        if ($request->activa == 0) {

            $request->validate([
                'name' => 'required|string|min:2|max:255',
                'last_name' => 'required|string|min:2|max:255',
                'telefono' => "required|string|max:255|unique:clientes,telefono,{$cliente->id}",
                'direccion' => 'nullable|string|min:2|max:255',
                'email' => "nullable|email|max:255|unique:clientes,email,{$cliente->id}",
                'tipo_cliente' => 'required|in:CLIENTE PÚBLICO,CLIENTE MEDIO MAYOREO,CLIENTE MAYOREO',
                'comentario' => 'nullable|string|min:2|max:1500',
                'ejecutivo_id' => 'nullable|integer',

                'dias_credito'   => 'nullable|integer|min:0',
                'limite_credito' => 'nullable|numeric|min:0',
            ]);

            // Validación personalizada para full_name
            $fullName = $request->name . ' ' . $request->last_name;

            // Asignar el nuevo valor al modelo
            $cliente->name = $request->name;
            $cliente->last_name = $request->last_name;
            $cliente->telefono = $request->telefono;
            $cliente->direccion = $request->direccion;
            $cliente->email = $request->email;
            $cliente->tipo_cliente = $request->tipo_cliente;
            $cliente->comentario = $request->comentario;
            $cliente->ejecutivo_id = $request->ejecutivo_id;
            $cliente->dias_credito = $request->dias_credito;
            $cliente->limite_credito = $request->limite_credito;

            if ($cliente->isDirty()) {
                // Validación personalizada para full_name
                //if (Cliente::where('full_name', $fullName)->exists()) {
                if (Cliente::where('full_name', $fullName)->where('id', '!=', $cliente->id)->exists()) {
                    return back()->withErrors(['full_name' => 'El cliente ya se encuentra registrado.'])->withInput();
                }

                try {
                    $cliente->name = $request->name;
                    $cliente->last_name = $request->last_name;
                    $cliente->full_name = $fullName;
                    $cliente->telefono = $request->telefono;
                    $cliente->direccion = $request->direccion;
                    $cliente->email = $request->email;
                    $cliente->tipo_cliente = $request->tipo_cliente;
                    $cliente->comentario = $request->comentario;
                    $cliente->ejecutivo_id = $request->ejecutivo_id;
                    $cliente->dias_credito = $request->dias_credito;
                    $cliente->limite_credito = $request->limite_credito;
                    $cliente->wci = auth()->user()->id;
                    $cliente->save();

                    session()->flash('swal', [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "El cliente se actualizó correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                        ],
                        'buttonsStyling' => false
                    ]);

                    return redirect()->route('admin.clientes.index');
                } catch (\Exception $e) {
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
            } else {
                session()->flash('swal', [
                    'icon' => "info",
                    'title' => "Sin cambios",
                    'text' => "No se realizaron cambios en el cliente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ]);

                return redirect()->route('admin.clientes.index');
            }
        }

        // ACTIVAMOS EL REGISTRO
        if ($request->activa == 1) {
            try {
                // Remueve los últimos 5 caracteres de 'full_name' , 'email' y 'telefono'
                $full_name = substr($cliente->full_name, 0, -6);
                $email = substr($cliente->email, 0, -6);
                $telefono = substr($cliente->telefono, 0, -6);

                // Verifica si 'full_name' y 'email' son únicos
                $isFullNameUnique = !Cliente::where('full_name', $full_name)
                    ->where('id', '!=', $cliente->id)
                    ->where('activo', 1) // Verificar solo entre los registros activos
                    ->exists();

                $isEmailUnique = !Cliente::where('email', $email)
                    ->where('id', '!=', $cliente->id)
                    ->where('activo', 1)
                    ->exists();

                $isTelefonoUnique = !Cliente::where('telefono', $telefono)
                    ->where('id', '!=', $cliente->id)
                    ->where('activo', 1)
                    ->exists();

                if (!$isFullNameUnique || !$isEmailUnique || !$isTelefonoUnique) {
                    // Almacena el mensaje de error en la sesión y redirige de vuelta
                    return response()->json([
                        'swal' => [
                            'icon' => "error",
                            'title' => "Error en la operación",
                            'text' => "El cliente, el correo electrónico ó el teléfono ya existen. Por favor, elija otro.",
                            'customClass' => [
                                'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                            ],
                            'buttonsStyling' => false
                        ],
                        'error' => "El cliente, el correo electrónico ó el teléfono ya existen. Por favor, elija otro.",
                    ], 400);
                }

                // Actualiza los campos necesarios
                $cliente->update([
                    'full_name' => $full_name,
                    'email' => $email,
                    'telefono' => $telefono,
                    'activo' => 1
                ]);

                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "El cliente se activo correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'La compra se eliminó correctamente.'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'swal' => [
                        'icon' => "error",
                        'title' => "Operación fallida",
                        'text' => "Hubo un error durante el proceso, por favor intente más tarde.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'error' => $e->getMessage(),
                ], 400);
            }
        }
    }

    public function destroy($id)
    {
        try {
            $cliente = Cliente::findorfail($id);
            if ($cliente->id > 1) {
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

                $cliente->update([
                    'full_name' => $cliente->full_name . '-' . substr(str_shuffle($permitted_chars), 0, 5),
                    'email' => $cliente->email . '-' . substr(str_shuffle($permitted_chars), 0, 5),
                    'telefono' => $cliente->telefono . '-' . substr(str_shuffle($permitted_chars), 0, 5),
                    'activo' => 0
                ]);
            } else {
                return redirect()->route('admin.clientes.index');
            }

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El cliente se eliminó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);
        } catch (\Exception $e) {
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
                ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
        }
    }

    public function clientes_index_ajax(Request $request)
    {
        // CLIENTES PARA EL INDEX
        if ($request->origen == 'clientes.index') {
            $clientes = Cliente::where('id', '!=', 1)->get()
                ->map(function ($item) {

                    // --- Etiqueta de Matriz ---
                $es_activo = $item->activo == 1
                    ? '<span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">Activo</span>'
                    : '<span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded">Eliminado</span>';

                return [
                    'id'        => $item->id,
                    'full_name' => $item->full_name,
                    'email' => $item->email,
                    'telefono' => $item->telefono,
                    'direccion' => $item->direccion,
                    'tipo_cliente' => $item->tipo_cliente,
                    'ejecutivo_id' => $item->ejecutivo_id,
                    'es_activo' => $es_activo,
                    'acciones' => e(view('gasto.partials.acciones', compact('item'))->render()),
                ];
            });

            return response()->json(['data' => $clientes]);
        }

        // CLIENTES PARA EL APARTADO DE COTIZACIONES
        if ($request->origen == 'clientes.cotizaciones') {
            $clientes = Cliente::where('activo', 1)
                ->get();

            return response()->json(['data' => $clientes]);
        }

        // CLIENTES PARA LOS PEDIDOS
        if ($request->origen == 'clientes.pedidos') {
            //$clientes = Cliente::where('activo', 1)
            //->get();

            $clientes = Cliente::with(['ventas' => function ($q) {
                $q->with(['credito', 'notaCreditos' => function ($qn) {
                    $qn->where('activo', true);
                }]);
            }])
                ->get()
                ->map(function ($cliente) {
                    $totalPendiente = 0;
                    $ventasCredito = 0;
                    $autoriza = true; // Por defecto autorizado

                    foreach ($cliente->ventas as $venta) {
                        $credito = $venta->credito;

                        if ($credito && !$credito->liquidado) {
                            $saldo = $credito->saldo_actual ?? 0;

                            // Revisar notas de crédito aplicadas
                            foreach ($venta->notaCreditos as $nota) {
                                $fechaLimiteNota = Carbon::parse($venta->fecha)->addDays($cliente->dias_credito ?? 0);
                                if (Carbon::now()->lessThanOrEqualTo($fechaLimiteNota) && $nota->activo) {
                                    $saldo -= $nota->monto ?? 0;
                                }
                            }

                            $saldo = max(0, $saldo);
                            $totalPendiente += $saldo;
                            $ventasCredito++;

                            // Verificar si la venta excede los dias_credito
                            $fechaLimiteVenta = Carbon::parse($venta->fecha)->addDays($cliente->dias_credito ?? 0);
                            if (Carbon::now()->greaterThan($fechaLimiteVenta) && $saldo > 0) {
                                $autoriza = false; // Venta vencida con saldo pendiente
                            }
                        }
                    }

                    // Clientes sin ventas a crédito se consideran autorizados
                    if ($ventasCredito === 0) {
                        $autoriza = true;
                    }

                    return [
                        'id'              => $cliente->id,
                        'full_name'       => $cliente->full_name,
                        'tipo_cliente'    => $cliente->tipo_cliente,
                        'ventas_credito'  => $ventasCredito,
                        'monto_pendiente' => $totalPendiente,
                        'limite_credito'  => $cliente->limite_credito,
                        'dias_credito'    => $cliente->dias_credito,
                        'autorizado'      => $autoriza,
                    ];
                });

            return response()->json(['data' => $clientes]);
        }
    }

    public function storeAjax(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'telefono'     => 'required|string|unique:clientes,telefono',
            'tipo_cliente' => 'required|in:CLIENTE PÚBLICO,CLIENTE MEDIO MAYOREO,CLIENTE MAYOREO',
            'ejecutivo_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            // devolver errores en JSON
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['full_name'] = $data['name'] . ' ' . $data['last_name'];
        $data['wci'] = auth()->id();

        $cliente = Cliente::create($data);

        return response()->json([
            'id' => $cliente->id,
            'full_name' => $cliente->full_name,
            'telefono' => $cliente->telefono,
        ]);
    }
}
