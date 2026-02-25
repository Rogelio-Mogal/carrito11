<?php

namespace App\Http\Controllers;

use App\Models\ProductoRecompenza;
use Illuminate\Http\Request;

class ProductoRecompenzaController extends Controller
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
        //
    }

    public function show(ProductoRecompenza $productoRecompenza)
    {
        //
    }

    public function edit(ProductoRecompenza $productoRecompenza)
    {
        //
    }

    public function update(Request $request, ProductoRecompenza $productoRecompenza)
    {
        //
    }

    public function destroy(ProductoRecompenza $productoRecompenza)
    {
        //
    }
}
