<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\VehiculosCombustible\VehiculoController;
use App\Http\Controllers\VehiculosCombustible\TareasController;
use App\Http\Controllers\VehiculosCombustible\MovimientoVehController;
use App\Http\Controllers\VehiculosCombustible\DespachoCombustibleController;
use App\Http\Controllers\VehiculosCombustible\PersonaController;
use App\Http\Controllers\VehiculosCombustible\PerfilController;
use App\Http\Controllers\VehiculosCombustible\UsuarioController;
use App\Http\Controllers\VehiculosCombustible\GestionController;
use App\Http\Controllers\VehiculosCombustible\MenuController;
use App\Http\Controllers\VehiculosCombustible\GestionMenuController;
use App\Http\Controllers\VehiculosCombustible\GasolineraController;
use App\Http\Controllers\VehiculosCombustible\GasolineraCombustibleController;
use App\Http\Controllers\VehiculosCombustible\CombustibleController;
use App\Http\Controllers\VehiculosCombustible\ReportesCombustibleController;


Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Auth::routes();

Route::middleware(['auth'])->group(function() { //middleware autenticacion

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


    //*****************************VEHICULOS COMBUSTIBLES****************//

    //PERSONA
    Route::get('/persona', [PersonaController::class, 'index'])->middleware('validarRuta');
    Route::get('/listado-persona', [PersonaController::class, 'listar']);
    Route::post('/guardar-persona', [PersonaController::class, 'guardar']);
    Route::get('/editar-persona/{id}', [PersonaController::class, 'editar']);
    Route::put('/actualizar-persona/{id}', [PersonaController::class, 'actualizar']);
    Route::get('/eliminar-persona/{id}', [PersonaController::class, 'eliminar']);


    //ROLES
    Route::get('/perfil', [PerfilController::class, 'index'])->middleware('validarRuta');
    Route::get('/listado-rol', [PerfilController::class, 'listar']);
    Route::post('/guardar-rol', [PerfilController::class, 'guardar']);
    Route::get('/editar-rol/{id}', [PerfilController::class, 'editar']);
    Route::put('/actualizar-rol/{id}', [PerfilController::class, 'actualizar']);
    Route::get('/eliminar-rol/{id}', [PerfilController::class, 'eliminar']);
    Route::get('/acceso-perfil/{id}', [PerfilController::class, 'accesoPerfil']);
    Route::get('/acceso-por-perfil/{menu}/{tipo}/{perfil}', [PerfilController::class, 'mantenimientoAccesoPerfil']);
    Route::get('/dato-perfil', [PerfilController::class, 'datoPerfil']);
    
    

    //GESTION
    Route::get('/gestion', [GestionController::class, 'index'])->middleware('validarRuta');
    Route::get('/listado-gestion', [GestionController::class, 'listar']);
    Route::post('/guardar-gestion', [GestionController::class, 'guardar']);
    Route::get('/editar-gestion/{id}', [GestionController::class, 'editar']);
    Route::put('/actualizar-gestion/{id}', [GestionController::class, 'actualizar']);
    Route::get('/eliminar-gestion/{id}', [GestionController::class, 'eliminar']);

    //MENU
    Route::get('/menu', [MenuController::class, 'index'])->middleware('validarRuta');
    Route::get('/listado-menu', [MenuController::class, 'listar']);
    Route::post('/guardar-menu', [MenuController::class, 'guardar']);
    Route::get('/editar-menu/{id}', [MenuController::class, 'editar']);
    Route::put('/actualizar-menu/{id}', [MenuController::class, 'actualizar']);
    Route::get('/eliminar-menu/{id}', [MenuController::class, 'eliminar']);

    //GESTION-MENU
    Route::get('/gestion-menu', [GestionMenuController::class, 'index'])->middleware('validarRuta');
    Route::get('/listado-gestion-menu', [GestionMenuController::class, 'listar']);
    Route::post('/guardar-gestion-menu', [GestionMenuController::class, 'guardar']);
    Route::get('/editar-gestion-menu/{id}', [GestionMenuController::class, 'editar']);
    Route::put('/actualizar-gestion-menu/{id}', [GestionMenuController::class, 'actualizar']);
    Route::get('/eliminar-gestion-menu/{id}', [GestionMenuController::class, 'eliminar']);


    //USUARIO
    Route::get('/usuario', [UsuarioController::class, 'index'])->middleware('validarRuta');
    Route::get('/listado-usuario', [UsuarioController::class, 'listar']);
    Route::post('/guardar-usuario', [UsuarioController::class, 'guardar']);
    Route::get('/editar-usuario/{id}', [UsuarioController::class, 'editar']);
    Route::put('/actualizar-usuario/{id}', [UsuarioController::class, 'actualizar']);
    Route::get('/eliminar-usuario/{id}', [UsuarioController::class, 'eliminar']);
    Route::post('/cambiar-clave', [UsuarioController::class, 'cambiarClave']);
    Route::get('/resetear-password/{id}', [UsuarioController::class, 'resetearPassword']);


    //VEHICULOS
    Route::get('/vehiculo', [VehiculoController::class, 'index'])->middleware('validarRuta');
    Route::get('/listado-vehiculo', [VehiculoController::class, 'listar']);
    Route::post('/guardar-vehiculo', [VehiculoController::class, 'guardar']);
    Route::get('/editar-vehiculo/{id}', [VehiculoController::class, 'editar']);
    Route::put('/actualizar-vehiculo/{id}', [VehiculoController::class, 'actualizar']);
    Route::get('/eliminar-vehiculo/{id}', [VehiculoController::class, 'eliminar']);

    //TAREAS
    Route::get('/tareas-vehiculo', [TareasController::class, 'index'])->middleware('validarRuta');
    Route::get('/listado-tarea', [TareasController::class, 'listar']);
    Route::post('/guardar-tarea', [TareasController::class, 'guardar']);
    Route::get('/editar-tarea/{id}', [TareasController::class, 'editar']);
    Route::put('/actualizar-tarea/{id}', [TareasController::class, 'actualizar']);
    Route::get('/eliminar-tarea/{id}', [TareasController::class, 'eliminar']);

    //ENTRADA-SALIDA
    Route::get('/entrada-salida-vehiculo', [MovimientoVehController::class, 'index'])->middleware('validarRuta');
    Route::get('/carga-tarea/{idvehi}', [MovimientoVehController::class, 'tareaVehiculo']);
    Route::post('/guardar-movimiento', [MovimientoVehController::class, 'guardar']);
    Route::get('/listado-movimiento', [MovimientoVehController::class, 'listar']);
    Route::get('/eliminar-movimiento/{id}', [MovimientoVehController::class, 'eliminar']);


    //GASOLINERAS
    Route::get('/gasolinera', [GasolineraController::class, 'index'])->middleware('validarRuta');
    Route::get('/listado-gasolinera', [GasolineraController::class, 'listar']);
    Route::post('/guardar-gasolinera', [GasolineraController::class, 'guardar']);
    Route::get('/editar-gasolinera/{id}', [GasolineraController::class, 'editar']);
    Route::put('/actualizar-gasolinera/{id}', [GasolineraController::class, 'actualizar']);
    Route::get('/eliminar-gasolinera/{id}', [GasolineraController::class, 'eliminar']);

    //COMBUSTIBLES
    Route::get('/combustible', [CombustibleController::class, 'index'])->middleware('validarRuta');
    Route::get('/listado-combustible', [CombustibleController::class, 'listar']);
    Route::post('/guardar-combustible', [CombustibleController::class, 'guardar']);
    Route::get('/editar-combustible/{id}', [CombustibleController::class, 'editar']);
    Route::put('/actualizar-combustible/{id}', [CombustibleController::class, 'actualizar']);
    Route::get('/eliminar-combustible/{id}', [CombustibleController::class, 'eliminar']);


    //GASOLINERA-COMBUSTIBLE
    Route::get('/gasolinera-combustible', [GasolineraCombustibleController::class, 'index'])->middleware('validarRuta');
    Route::get('/listado-gasolinera-combustible', [GasolineraCombustibleController::class, 'listar']);
    Route::post('/guardar-gasolinera-combustible', [GasolineraCombustibleController::class, 'guardar']);
    Route::get('/editar-gasolinera-combustible/{id}', [GasolineraCombustibleController::class, 'editar']);
    Route::put('/actualizar-gasolinera-combustible/{id}', [GasolineraCombustibleController::class, 'actualizar']);
    Route::get('/eliminar-gasolinera-combustible/{id}', [GasolineraCombustibleController::class, 'eliminar']);


    //DESPACHO COMBUSTIBLE
    Route::get('/despacho-combustible', [DespachoCombustibleController::class, 'index'])->middleware('validarRuta');
    Route::post('/guardar-cab-despacho', [DespachoCombustibleController::class, 'guardarCabecera']);
    Route::get('/editar-cab-despacho/{id}', [DespachoCombustibleController::class, 'editarCabecera']);
    Route::put('/actualizar-cab-despacho/{id}', [DespachoCombustibleController::class, 'actualizarCabecera']);
    Route::get('/eliminar-cab-despacho/{id}', [DespachoCombustibleController::class, 'eliminarCabecera']);
    Route::get('/listado-desp', [DespachoCombustibleController::class, 'listar']);
    Route::get('/precio-detalle-comb/{idVeh}/{idGas}', [DespachoCombustibleController::class, 'detallePrecioComb']);
    Route::get('/precio-comb-gas/{idCom}/{idGas}', [DespachoCombustibleController::class, 'PrecioCombGaso']);
    Route::post('/guardar-detalle-desp', [DespachoCombustibleController::class, 'guardarDetalle']);
    Route::get('/detalle-listado-des/{idCabDesp}', [DespachoCombustibleController::class, 'listarDetalleDesp']);
    Route::get('/detalle-desp/editar/{idDetalle}', [DespachoCombustibleController::class, 'editarDetalle']);
    Route::put('/actualizar-detalle-desp/{idDetalle}', [DespachoCombustibleController::class, 'actualizarDetalle']);
    Route::get('/eliminar-detalle-desp/{idDetalle}', [DespachoCombustibleController::class, 'eliminarDetalle']);
    Route::get('/listar-tarea-veh/{idVeh}/{fecha}', [DespachoCombustibleController::class, 'listarTareaVeh']);
    Route::post('/aprobar-despacho-firma', [DespachoCombustibleController::class, 'aprobarDespacho']);
    Route::get('/despacho-pdf/{id}', [DespachoCombustibleController::class, 'despachoPdfGasolinera']);


    //FORMULARIO REPORTES
    Route::get('/reportes-formularios', [ReportesCombustibleController::class, 'index'])->middleware('validarRuta');
    Route::get('/listado-reportes', [ReportesCombustibleController::class, 'listado']);
    Route::post('/guardar-reportes', [ReportesCombustibleController::class, 'guardar']);
    Route::get('/descargar-reporte-form/{id}', [ReportesCombustibleController::class, 'descargar']);
   


});