<?php

use App\Models\Preventa;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PreventaController;
use App\Http\Controllers\PropuestaController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\CampanaController;
use \App\Http\Controllers\Agricultor\AnalisisController;
use App\Http\Controllers\Admin\CasetaController;
use App\Http\Controllers\Admin\TipoArrozController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Definimos las variables por defecto
    $preventasActivas = 0;
    $propuestasRecibidas = 0;
    $ventasCompletadas = 0;

    // Verificamos el rol del usuario que ha iniciado sesión
    if (auth()->user()->rol === 'agricultor') {
        // Si es agricultor, calculamos sus datos
        $preventasActivas = Preventa::where('user_id', auth()->id())
            ->where('estado', 'activa')
            ->count();
        // (Añadiremos la lógica de propuestas y ventas más adelante)

    } elseif (auth()->user()->rol === 'molino') {
        // Si es molino, calculamos sus datos
        // Por ahora, le pasaremos ceros como placeholders
        $preventasActivas = Preventa::where('estado', 'activa')->count(); // Total del mercado
        // (Añadiremos la lógica de compras y campañas más adelante)
    }

    // Pasamos las variables a la vista
    return view('dashboard', [
        'preventasActivas' => $preventasActivas,
        'propuestasRecibidas' => $propuestasRecibidas,
        'ventasCompletadas' => $ventasCompletadas,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('preventas', PreventaController::class);
    Route::post('/preventas/{preventa}/aceptar', [PreventaController::class, 'accept'])->name('preventas.accept');
    Route::post('propuestas', [PropuestaController::class, 'store'])->name('propuestas.store');
    Route::get('/mercado', [PreventaController::class, 'mercado'])->name('mercado.index');

    Route::post('propuestas/{propuesta}/aceptar', [PropuestaController::class, 'accept'])->name('propuestas.accept');
    Route::post('propuestas/{propuesta}/rechazar', [PropuestaController::class, 'reject'])->name('propuestas.reject');

    Route::get('/mis-negociaciones', [PreventaController::class, 'negociaciones'])->name('preventas.negociaciones');

    Route::resource('campanas', CampanaController::class);
    
    Route::resource('lotes', LoteController::class);
    Route::get('/mis-liquidaciones', [\App\Http\Controllers\Agricultor\PagoController::class, 'index'])->name('agricultor.pagos.index');

    Route::resource('cuentas-bancarias', \App\Http\Controllers\CuentaBancariaController::class)->except(['show']);

    Route::post('cuentas-bancarias/{cuentaBancaria}/set-primary', [\App\Http\Controllers\CuentaBancariaController::class, 'setPrimary'])->name('cuentas-bancarias.setPrimary');

    Route::get('/mercado-campanas', [CampanaController::class, 'mercadoParaAgricultores'])->name('campanas.mercado');


    Route::get('/mercado-campanas', [CampanaController::class, 'mercadoParaAgricultores'])->name('campanas.mercado');

    Route::get('campanas/{campana}/aplicaciones', [CampanaController::class, 'verAplicaciones'])->name('campanas.aplicaciones');
    Route::post('aplicaciones/{aplicacion}/aprobar', [CampanaController::class, 'aprobarAplicacion'])->name('aplicaciones.aprobar');
    Route::post('aplicaciones/{aplicacion}/rechazar', [CampanaController::class, 'rechazarAplicacion'])->name('aplicaciones.rechazar');
    Route::post('/campanas/{campana}/aplicar', [CampanaController::class, 'aplicar'])->name('campanas.aplicar');

    Route::get('/mis-certificados', [AnalisisController::class, 'index'])->name('agricultor.analisis.index');

    Route::get('/mis-pagos', [\App\Http\Controllers\Molino\PagoController::class, 'index'])->name('molino.pagos.index');
    Route::get('/pagar-carga/{id}', [\App\Http\Controllers\Molino\PagoController::class, 'create'])->name('molino.pagos.create');
    Route::post('/pagar-carga/{id}', [\App\Http\Controllers\Molino\PagoController::class, 'store'])->name('molino.pagos.store');


    //Rutas para el administrador
    Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {

        Route::resource('tipos-arroz', TipoArrozController::class);

        Route::resource('casetas', CasetaController::class);

        Route::resource('usuarios-caseta', \App\Http\Controllers\Admin\CasetaUsuarioController::class);
                                   
    });
});

// --- RUTAS OPERATIVAS DE CASETA ---
Route::middleware(['auth', 'verified'])->prefix('caseta')->name('caseta.')->group(function () {
    
    // 1. Pantalla de Selección (Donde pone el código)
    Route::get('/seleccion', [\App\Http\Controllers\Caseta\OperacionesController::class, 'showSelector'])->name('seleccion');

    // 2. Proceso de validación del código
    Route::post('/seleccionar', [\App\Http\Controllers\Caseta\OperacionesController::class, 'setCaseta'])->name('set');

    // 3. Dashboard Operativo (Donde trabajará)
    Route::get('/dashboard', [\App\Http\Controllers\Caseta\OperacionesController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/recepcion', [\App\Http\Controllers\Caseta\OperacionesController::class, 'recepcion'])->name('recepcion');

    Route::get('/evaluar/{id}', [\App\Http\Controllers\Caseta\OperacionesController::class, 'evaluar'])->name('evaluar');

    Route::post('/evaluar/{id}', [\App\Http\Controllers\Caseta\OperacionesController::class, 'guardarAnalisis'])->name('guardar-analisis');
});

require __DIR__ . '/auth.php';
