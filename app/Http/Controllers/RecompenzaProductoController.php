<?php

namespace App\Http\Controllers;

use App\Models\RecompenzaProducto;
use Illuminate\Http\Request;

class RecompenzaProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware(['can:Gestión de roles']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RecompenzaProducto $recompenzaProducto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RecompenzaProducto $recompenzaProducto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RecompenzaProducto $recompenzaProducto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RecompenzaProducto $recompenzaProducto)
    {
        //
    }
}
