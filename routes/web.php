<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoordinacionController;
use App\Http\Controllers\ComponenteController;
use App\Http\Controllers\ComponenteOpcionalController;
use App\Http\Controllers\DireccionController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EquipoPdfController;
use App\Http\Controllers\EstadoFuncionalPdfController;
use App\Http\Controllers\EstadoTecnologicoPdfController;
use App\Http\Controllers\EstadoGabinetePdfController;
use App\Http\Controllers\PdfInactivosController;

// Rutas públicas
// Ruta principal
Route::get('/login', [LoginController::class, 'index'])->name('login.index');

// Ruta alias solo para el middleware
Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::post('/login', [LoginController::class, 'autenticar'])->name('login.autenticar');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

    // Direcciones CRUD
    Route::resource('direcciones', DireccionController::class)
        ->parameters(['direcciones' => 'direccion'])
        ->except(['show']);

    // web.php
    Route::get('/coordinaciones/divisiones/{idDireccion}', [AjaxController::class, 'divisiones']);

    // Divisiones CRUD
    Route::resource('divisiones', DivisionController::class)->except(['show']);

    // Coordinaciones CRUD
    Route::resource('coordinaciones', CoordinacionController::class)->except(['show']);
    Route::get('coordinaciones/by-division/{id_division}', [CoordinacionController::class, 'getByDivisionAjax'])
        ->name('coordinaciones.byDivision');

    // Equipos CRUD
    Route::resource('equipos', EquipoController::class)->except(['show']);
    Route::get('equipos/by-coordinacion/{id_coordinacion}', [EquipoController::class, 'getByCoordinacionAjax'])
        ->name('equipos.byCoordinacion');

    Route::get('/equipos/inactivos', [App\Http\Controllers\EquipoController::class, 'indexInactivos'])
        ->name('equipos.inactivos')
        ->middleware('auth');

    // Componentes CRUD
    Route::resource('componentes', ComponenteController::class)->except(['show']);

    // Mostrar componentes de un equipo específico
    Route::get('componentes/por-equipo/{id_equipo}', [ComponenteController::class, 'porEquipo'])
        ->name('componentes.porEquipo')
        ->middleware('auth');

    Route::get('/componentes/unicos/{id_equipo}', [ComponenteController::class, 'getComponentesUnicosPorEquipo'])
        ->middleware('auth'); // si tu controller requiere auth

    Route::get('componentes/por-equipo/{id_equipo}/create', [ComponenteController::class, 'createPorEquipo'])
        ->name('componentes.createPorEquipo');

    Route::get('componentes/por-equipo/{id}/edit', [ComponenteController::class, 'editPorEquipo'])
        ->name('componentes.editPorEquipo');

    Route::get('componentesOpcionales/por-equipo/{id_equipo}', [ComponenteOpcionalController::class, 'porEquipo'])
        ->name('componentesOpcionales.porEquipo')
        ->middleware('auth');

    Route::get('componentesOpcionales/por-equipo/{id_equipo}/create', [ComponenteOpcionalController::class, 'createPorEquipo'])
        ->name('componentesOpcionales.createPorEquipo');

    // Editar componente opcional normal
    Route::get('/componentesOpcionales/{id}/edit', [ComponenteOpcionalController::class, 'edit'])
        ->name('componentesOpcionales.edit');

    // Editar componente opcional por equipo
    Route::get('/componentesOpcionales/por-equipo/{id}/edit', [ComponenteOpcionalController::class, 'editPorEquipo'])
        ->name('componentesOpcionales.editPorEquipo');

    Route::resource('componentesOpcionales', ComponenteOpcionalController::class)->except(['show']);

    // Usuarios CRUD
    Route::resource('usuarios', UsuarioController::class)->except(['show']);

    // Logs
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
    Route::delete('logs/{id}', [LogController::class, 'destroy'])->name('logs.destroy');

    //PDF
    Route::get('/equipos/{id}/pdf', [EquipoPdfController::class, 'generarPDF'])->name('equipos.pdf');

    Route::get('/estado-tecnologico/pdf', [EstadoTecnologicoPdfController::class, 'generarPDF'])
        ->name('estado-tecnologico.pdf');

    Route::get('/estado-funcional/pdf', [EstadoFuncionalPdfController::class, 'generarPDF'])
        ->name('estado-funcional.pdf');

    Route::get('/estado-gabinete/pdf', [EstadoGabinetePdfController::class, 'generarPDF'])
        ->name('estado-gabinete.pdf');

    Route::get('/pdf/equipos-inactivos', [PdfInactivosController::class, 'exportar'])
        ->name('pdf.equipos.inactivos');
});
