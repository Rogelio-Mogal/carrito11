<?php

namespace App\Http\Controllers;

use App\Models\FormaPago;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FormaPagoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        return view('forma_pago.index');
    }

    public function create()
    {
        $formapago = new FormaPago();
        $metodo = 'create';

        return view('forma_pago.create', compact('formapago','metodo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'forma_pago' => 'required|string|max:255|unique:forma_pagos',
        ]);
        try{
            $formapago = new FormaPago();
            $formapago->forma_pago = $request->forma_pago;
            $formapago->save();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La forma de pago se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.forma.pago.index');
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
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $formapago = FormaPago::findorfail($id);
        $metodo = 'edit';
        return view('forma_pago.edit', compact('formapago','metodo'));

    }

    public function update(Request $request, $id)
    {
        $formapago = FormaPago::findorfail($id);
        // ACTUALIZAMOS EL REGISTRO
        if ($request->activa == 0){

            $request->validate([
                'forma_pago' => "required|string|max:255|unique:forma_pagos,forma_pago,{$formapago->id}",
            ]);

            // Asignar el nuevo valor al modelo
            $formapago->forma_pago = $request->forma_pago;

            if ($formapago->isDirty()) {
                try{
                    $formapago->forma_pago = $request->forma_pago;
                    $formapago->updated_at = Carbon::now();
                    $formapago->save();

                    session()->flash('swal', [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "La forma de pago se actualizó correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                        ],
                        'buttonsStyling' => false
                    ]);

                    return redirect()->route('admin.forma.pago.index');
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
                    'text' => "No se realizaron cambios en la forma de pago.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ]);

                return redirect()->route('admin.forma.pago.index');
            }
        }

        // ACTIVAMOS EL REGISTRO
        if ($request->activa == 1){
            try {
                // Remueve los últimos 5 caracteres de 'forma_pago'
                $forma_pago = substr($formapago->forma_pago, 0, -6);


                // Verifica si 'forma_pago'  son únicos
                $isFormaPagoUnique = !FormaPago::where('forma_pago', $forma_pago)
                    ->where('id', '!=', $formapago->id)
                    ->where('activo', 1) // Verificar solo entre los registros activos
                    ->exists();

                if (!$isFormaPagoUnique) {
                    // Almacena el mensaje de error en la sesión y redirige de vuelta
                    return response()->json([
                        'swal' => [
                            'icon' => "error",
                            'title' => "Error en la operación",
                            'text' => "La forma de pago ya existe. Por favor, elija otro.",
                            'customClass' => [
                                'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                            ],
                            'buttonsStyling' => false
                        ],
                        'error' => "La forma de pago ya existe. Por favor, elija otro.",
                    ], 400);
                }

                // Actualiza los campos necesarios
                $formapago->update([
                    'forma_pago' => $forma_pago,
                    'activo' => 1
                ]);

                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "La forma de pago se activo correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'La forma de pago se activo correctamente.'
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
            $formapago = FormaPago::findorfail($id);

            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $formapago->update([
                'forma_pago' => $formapago->forma_pago.'-'.substr(str_shuffle($permitted_chars), 0, 5),
                'activo' => 0
            ]);

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La forma de pago se eliminó correctamente.",
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

    public function forma_pago_index_ajax(Request $request)
    {
        if ($request->origen == 'formaPago.index') {

            $ventas = FormaPago::all()
                ->map(function ($item) {

                    // --- Etiqueta de Matriz ---
                $es_activo = $item->activo == 1
                    ? '<span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">Activo</span>'
                    : '<span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded">Eliminado</span>';

                return [
                    'id'        => $item->id,
                    'forma_pago' => $item->forma_pago,
                    'es_activo' => $es_activo,
                    'acciones' => e(view('forma_pago.partials.acciones', compact('item'))->render()),
                ];
            });

            return response()->json([ 'data' => $ventas ]);
        }
    }
}
