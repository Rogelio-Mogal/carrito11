<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\User;
use Illuminate\Http\Request;

class NotaVentaPcController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        $notaVentasPc = Documento::where('id', '>', 1)
            ->get();

        foreach ($notaVentasPc as $ticket) {
            $ticket->usuario_nombre = User::find($ticket->wci)->name;
        }

        return view('nota_venta_pc.index', compact('notaVentasPc'));
    }

    public function create(Request $request)
    {
        $notaVentaPc = new Documento;
        $metodo = 'create';
        return view('nota_venta_pc.create', compact('notaVentaPc', 'metodo'));
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
