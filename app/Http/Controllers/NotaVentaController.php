<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\User;
use Illuminate\Http\Request;

class NotaVentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        $notaVentas = Documento::where('id', '>', 1)
            ->get();

        foreach ($notaVentas as $ticket) {
            $ticket->usuario_nombre = User::find($ticket->wci)->name;
        }

        return view('nota_venta.index', compact('notaVentas'));
    }

    public function create(Request $request)
    {
        $notaVenta = new Documento;
        $metodo = 'create';
        return view('nota_venta.create', compact('notaVenta', 'metodo'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Documento $documento)
    {
        //
    }

    public function edit(Documento $documento)
    {
        //
    }

    public function update(Request $request, Documento $documento)
    {
        //
    }

    public function destroy(Documento $documento)
    {
        //
    }
}
