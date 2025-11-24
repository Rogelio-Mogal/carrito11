<?php

use App\Http\Controllers\AbonosController;
use App\Http\Controllers\AnticipoController;
use App\Http\Controllers\ApartadoController;
use App\Http\Controllers\AsignarGastosController;
use App\Http\Controllers\AtributoController;
use App\Http\Controllers\CajaMovimientoController;
use App\Http\Controllers\CajaTurnoController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\CotizacionesController;
use App\Http\Controllers\CotizacionesDetallesController;
use App\Http\Controllers\FamiliaAtributoController;
use App\Http\Controllers\FinanzasController;
use App\Http\Controllers\FormaPagoController;
use App\Http\Controllers\GarantiaController;
use App\Http\Controllers\GastosController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\NotaCreditoController;
use App\Http\Controllers\NotaVentaController;
use App\Http\Controllers\NotaVentaPcController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PreciosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ProductoCaracteristicasController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\ReparacionController;
use App\Http\Controllers\SucursalesController;
use App\Http\Controllers\TicketAlternoController;
use App\Http\Controllers\TipoGastoController;
use App\Http\Controllers\VentaCreditoController;
use App\Http\Controllers\VentasController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUserActive;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/prueba', function () {
    session()->flash('swal', [
        'icon' => "error",
        'title' => "Oops...",
        'text' => "Something went wrong!",
        'footer' => '<a href="#">Why do I have this issue?</a>'
    ]);
    return view('boostrap.componente');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckUserActive::class
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::post('/update-menu-color', [UserProfileController::class, 'updateMenuColor'])->middleware('auth');
Route::post('/update-theme', [UserProfileController::class, 'updateTheme'])->middleware('auth');

Route::get('/compara-precios', [PreciosController::class, 'comparaRangosPrecios'])
    ->name('compara.precios');

Route::get('busca/factura/{id}', [ComprasController::class, 'busca_factura_duplicada'])
	->name('busca.factura.duplicada');

Route::get('busca/num/serie/{id}', [ComprasController::class, 'numero_serie_duplicado'])
	->name('busca.num.serie.duplicado');

Route::post('busca/producto/servicio/codbarra', [ProductosController::class, 'busca_codbarra_productos_compra'])
	->name('busca.producto.servicio.codbarra');

/*Route::get('productos/compra', [ComprasController::class, 'productos_compra'])/
	->name('producto.compra');*/

Route::post('productos/index/ajax', [ProductosController::class, 'productos_index_ajax'])
	->name('productos.index.ajax');

Route::get('/obtener-precios', [PreciosController::class, 'obtenerPrecios'])
		->name('obtener.precios');

Route::post('clientes/index/ajax', [ClientesController::class, 'clientes_index_ajax'])
	->name('clientes.index.ajax');

Route::resource('/roles', RoleController::class)->names('admin.roles')
    ->except(['show']);
Route::resource('/permissions', PermissionController::class)->names('admin.permissions')
    ->except(['show']);
Route::resource('/users', UserController::class)->names('admin.users')
    ->except(['show']);
Route::resource('/perfil', UserProfileController::class)
    ->except(['index', 'create', 'store', 'show', 'destroy'])
    ->parameters(['perfil' => 'perfil'])
    ->names('admin.perfil');
Route::resource('/producto-caracteristica', ProductoCaracteristicasController::class)->names('admin.producto.caracteristica')
    ->except(['show']);
Route::resource('/proveedores', ProveedoresController::class)->names('admin.proveedores')
    ->except(['show']);
Route::resource('/producto-servicio', ProductosController::class)->names('admin.producto.servicio');
Route::resource('/precios', PreciosController::class)->names('admin.precios');
Route::resource('/compras', ComprasController::class)->names('admin.compras');
Route::resource('/inventario', InventarioController::class)->names('admin.inventario')
    ->except(['create', 'store', 'show', 'destroy']);

Route::post('atributo/index/ajax', [AtributoController::class, 'atributo_index_ajax'])
	->name('atributo.index.ajax');

Route::post('familia-atributo/index/ajax', [FamiliaAtributoController::class, 'familia_atributo_index_ajax'])
	->name('familia.atributo.index.ajax');

Route::get('/familias/{familia}/atributos', [ProductosController::class, 'getAtributosByFamilia']);

// Cancelar toda la venta
Route::post('ventas/{venta}/cancelar', [VentasController::class, 'cancelarVenta'])->name('admin.ventas.cancelarVenta');
//Route::post('admin/ventas/{venta}/cancelar', [VentasController::class, 'cancelarVenta'])
//    ->name('admin.ventas.cancelar');

// Cancelar solo un producto de la venta
Route::post('ventas/producto/{detalle}/cancelar', [VentasController::class, 'cancelarProducto'])->name('admin.ventas.cancelarProducto');
//Route::post('admin/ventas/producto/{detalle}/cancelar', [VentasController::class, 'cancelarProducto'])
//    ->name('admin.ventas.cancelarProducto');

Route::get('ticket-venta/{id}', [VentasController::class, 'ticket'])
	->name('ticket.venta');

Route::post('garantia/index/ajax', [GarantiaController::class, 'garantia_index_ajax'])
	->name('garantias.index.ajax');

Route::post('notas-credito/index/ajax', [NotaCreditoController::class, 'nota_credito_index_ajax'])
	->name('nota.credito.ajax');

Route::get('/buscar-venta', [VentasController::class, 'buscarVenta']);

Route::get('garantias/{garantia}/solucion', [GarantiaController::class, 'agregarSolucion'])
    ->name('admin.garantias.solucion');

Route::post('/garantia/verificar-cambio', [GarantiaController::class, 'verificarExistenciaCambioFisico'])->name('garantia.verificar-cambio');

Route::get('ticket-garantia/{id}', [GarantiaController::class, 'ticket'])
	->name('ticket.garantia');

Route::get('ticket-nota-credito/{id}', [NotaCreditoController::class, 'ticket'])
	->name('ticket.nota.credito');

Route::get('ticket-anticipo/{id}', [AnticipoController::class, 'ticket'])
->name('ticket.anticipo');

Route::get('ticket-apartado/{id}', [ApartadoController::class, 'ticket'])
->name('ticket.apartado');

Route::get('ticket-abono/{id}', [AbonosController::class, 'ticket'])
->name('ticket.abono');

Route::get('ticket-reparacion/{id}', [ReparacionController::class, 'ticket'])
->name('ticket.reparacion');

Route::post('/clientes/store-ajax', [ClientesController::class, 'storeAjax'])->name('clientes.store.ajax');

Route::post('anticipo/index/ajax', [AnticipoController::class, 'anticipo_index_ajax'])
	->name('anticipo.apartado.index.ajax');

Route::post('anticipo/abono/store', [AbonosController::class, 'storeAnticipo'])
    ->name('admin.anticipo.abono.store');

Route::post('apartado/index/ajax', [ApartadoController::class, 'apartado_index_ajax'])
	->name('apartado.index.ajax');

Route::post('reparacion/index/ajax', [ReparacionController::class, 'reparador_index_ajax'])
	->name('reparador.index.ajax');

Route::post('/reparacion/asignar-reparador', [ReparacionController::class, 'asignarReparador'])
    ->name('reparador.asignar');

Route::post('apartado/abono/store', [AbonosController::class, 'storeApartado'])
    ->name('admin.apartado.abono.store');

Route::post('/users/toggle-reparador', [UserController::class, 'toggleReparador'])->name('users.toggleReparador');
Route::post('/users/toggle-externo', [UserController::class, 'toggleExterno'])->name('users.toggleExterno');

Route::post('/reparacion/pagar-servicio', [ReparacionController::class, 'pagarServicio'])->name('reparacion.pagar.servicio');

Route::get('reparacion/{id}/solucion', [ReparacionController::class, 'solucion'])
    ->name('admin.reparacion.solucion');

Route::post('caja-movimeinto/index/ajax', [CajaMovimientoController::class, 'caja_index_ajax'])
	->name('caja.index.ajax');

Route::get('/productos/buscar-para-tabla', [ProductosController::class, 'buscarProductoPorCodigo'])
    ->name('productos.cod.barra');

Route::post('sucursal/index/ajax', [SucursalesController::class, 'sucursal_index_ajax'])
	->name('sucursal.index.ajax');

Route::post('usuario/index/ajax', [UserController::class, 'usuarios_index_ajax'])
	->name('usuario.index.ajax');

Route::resource('/clientes', ClientesController::class)->names('admin.clientes')
->except('show');

Route::resource('/forma-pago', FormaPagoController::class)->names('admin.forma.pago')
->except('show');

Route::resource('/tipo-gasto', TipoGastoController::class)->names('admin.tipo.gasto')
->except('show');

Route::resource('/gastos', GastosController::class)->names('admin.gastos')
->except('show');

Route::resource('/asignar-gasto', AsignarGastosController::class)->names('admin.asignar.gasto')
->except(['show','edit']);

Route::resource('cotizaciones', CotizacionesController::class)->names('admin.cotizacion');
Route::resource('cotizacion/detalles', CotizacionesDetallesController::class)->names('admin.cotizacion.detalles');
Route::resource('ticket-alterno', TicketAlternoController::class)->names('admin.ticket.alterno');
Route::resource('nota-venta', NotaVentaController::class)->names('admin.nota.venta');
Route::resource('venta-pc-nota', NotaVentaPcController::class)->names('admin.nota.pc.venta');
Route::resource('sucursales', SucursalesController::class)->names('admin.sucursales');
Route::resource('atributos', AtributoController::class)->names('admin.atributos');
Route::resource('familia-atributos', FamiliaAtributoController::class)->names('admin.familia.atributos');
Route::resource('ventas', VentasController::class)->names('admin.ventas');
Route::resource('garantias', GarantiaController::class)->names('admin.garantias');
Route::resource('nota-credito', NotaCreditoController::class)->names('admin.nota.credito');
Route::resource('caja-movimeinto', CajaMovimientoController::class)->names('admin.caja.movimiento');
Route::resource('venta-credito', VentaCreditoController::class)->names('admin.venta.credito');
Route::resource('venta-credito-abono', AbonosController::class)->names('admin.venta.credito.abono');
Route::resource('anticipo', AnticipoController::class)->names('admin.anticipo');
Route::resource('apartado', ApartadoController::class)->names('admin.apartado');
Route::resource('reparacion', ReparacionController::class)->names('admin.reparacion');
Route::resource('caja-turno', CajaTurnoController::class)->names('admin.caja.turno');

