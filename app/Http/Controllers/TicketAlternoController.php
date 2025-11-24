<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\User;
use Illuminate\Http\Request;

class TicketAlternoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        $tickets = Documento::where('id', '>', 1)
            ->get();

        foreach ($tickets as $ticket) {
            $ticket->usuario_nombre = User::find($ticket->wci)->name;
        }

        return view('ticket_alterno.index', compact('tickets'));
    }

    public function create(Request $request)
    {
        $ticket = new Documento;
        $metodo = 'create';
        return view('ticket_alterno.create', compact('ticket', 'metodo'));
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
