<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\User;
use Illuminate\Http\Request;

class NotaVentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:nota_venta.ver')
        ->only(['index', 'show']);

        $this->middleware('permission:nota_venta.crear')
            ->only(['create', 'store']);

        $this->middleware('permission:nota_venta.editar')
            ->only(['edit', 'update']);

        $this->middleware('permission:nota_venta.eliminar')
            ->only(['destroy']);
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
