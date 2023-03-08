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


Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Auth::routes();

Route::middleware(['validarRuta'])->group(function() { //middleware para validar el acceso a las rutas

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //Pacientes
    Route::get('/registro-paciente', [PacienteController::class, 'index']);
    Route::get('/obtener-canton-prov/{idprov}', [PacienteController::class, 'obtenerCantones']);
    Route::get('/obtener-parroquia-canton/{idcanton}', [PacienteController::class, 'obtenerParroquias']);
    Route::post('/guardar-paciente', [PacienteController::class, 'guardar']);
    Route::post('/guardar-generarf1', [PacienteController::class, 'guardarGeneraraF1']);
    Route::get('/busqueda', [PacienteController::class, 'busqueda'])->name('home');
    Route::get('/buscarPaciente', [PacienteController::class, 'busquedaPaciente']);
    Route::get('/info-paciente/{idpac}', [PacienteController::class, 'infoPaciente']);
    Route::put('/actualiza-paciente/{idpac}', [PacienteController::class, 'actualiza']);


    //*****************************VEHICULOS COMBUSTIBLES****************//

    //PERSONA
    Route::get('/persona', [PersonaController::class, 'index']);
    Route::get('/listado-persona', [PersonaController::class, 'listar']);
    Route::post('/guardar-persona', [PersonaController::class, 'guardar']);
    Route::get('/editar-persona/{id}', [PersonaController::class, 'editar']);
    Route::put('/actualizar-persona/{id}', [PersonaController::class, 'actualizar']);
    Route::get('/eliminar-persona/{id}', [PersonaController::class, 'eliminar']);


    //ROLES
    Route::get('/perfil', [PerfilController::class, 'index']);
    Route::get('/listado-rol', [PerfilController::class, 'listar']);
    Route::post('/guardar-rol', [PerfilController::class, 'guardar']);
    Route::get('/editar-rol/{id}', [PerfilController::class, 'editar']);
    Route::put('/actualizar-rol/{id}', [PerfilController::class, 'actualizar']);
    Route::get('/eliminar-rol/{id}', [PerfilController::class, 'eliminar']);
    Route::get('/acceso-perfil/{id}', [PerfilController::class, 'accesoPerfil']);
    Route::get('/acceso-por-perfil/{menu}/{tipo}/{perfil}', [PerfilController::class, 'mantenimientoAccesoPerfil']);

    //GESTION
    Route::get('/gestion', [GestionController::class, 'index']);
    Route::get('/listado-gestion', [GestionController::class, 'listar']);
    Route::post('/guardar-gestion', [GestionController::class, 'guardar']);
    Route::get('/editar-gestion/{id}', [GestionController::class, 'editar']);
    Route::put('/actualizar-gestion/{id}', [GestionController::class, 'actualizar']);
    Route::get('/eliminar-gestion/{id}', [GestionController::class, 'eliminar']);

    //MENU
    Route::get('/menu', [MenuController::class, 'index']);
    Route::get('/listado-menu', [MenuController::class, 'listar']);
    Route::post('/guardar-menu', [MenuController::class, 'guardar']);
    Route::get('/editar-menu/{id}', [MenuController::class, 'editar']);
    Route::put('/actualizar-menu/{id}', [MenuController::class, 'actualizar']);
    Route::get('/eliminar-menu/{id}', [MenuController::class, 'eliminar']);

    //GESTION-MENU
    Route::get('/gestion-menu', [GestionMenuController::class, 'index']);
    Route::get('/listado-gestion-menu', [GestionMenuController::class, 'listar']);
    Route::post('/guardar-gestion-menu', [GestionMenuController::class, 'guardar']);
    Route::get('/editar-gestion-menu/{id}', [GestionMenuController::class, 'editar']);
    Route::put('/actualizar-gestion-menu/{id}', [GestionMenuController::class, 'actualizar']);
    Route::get('/eliminar-gestion-menu/{id}', [GestionMenuController::class, 'eliminar']);


    //USUARIO
    Route::get('/usuario', [UsuarioController::class, 'index']);
    Route::get('/listado-usuario', [UsuarioController::class, 'listar']);
    Route::post('/guardar-usuario', [UsuarioController::class, 'guardar']);
    Route::get('/editar-usuario/{id}', [UsuarioController::class, 'editar']);
    Route::put('/actualizar-usuario/{id}', [UsuarioController::class, 'actualizar']);
    Route::get('/eliminar-usuario/{id}', [UsuarioController::class, 'eliminar']);


    //VEHICULOS
    Route::get('/vehiculo', [VehiculoController::class, 'index']);
    Route::get('/listado-vehiculo', [VehiculoController::class, 'listar']);
    Route::post('/guardar-vehiculo', [VehiculoController::class, 'guardar']);
    Route::get('/editar-vehiculo/{id}', [VehiculoController::class, 'editar']);
    Route::put('/actualizar-vehiculo/{id}', [VehiculoController::class, 'actualizar']);
    Route::get('/eliminar-vehiculo/{id}', [VehiculoController::class, 'eliminar']);

    //TAREAS
    Route::get('/tareas-vehiculo', [TareasController::class, 'index']);
    Route::get('/listado-tarea', [TareasController::class, 'listar']);
    Route::post('/guardar-tarea', [TareasController::class, 'guardar']);
    Route::get('/editar-tarea/{id}', [TareasController::class, 'editar']);
    Route::put('/actualizar-tarea/{id}', [TareasController::class, 'actualizar']);
    Route::get('/eliminar-tarea/{id}', [TareasController::class, 'eliminar']);

    //ENTRADA-SALIDA
    Route::get('/entrada-salida-vehiculo', [MovimientoVehController::class, 'index']);
    Route::get('/carga-tarea/{idvehi}', [MovimientoVehController::class, 'tareaVehiculo']);
    Route::post('/guardar-movimiento', [MovimientoVehController::class, 'guardar']);
    Route::get('/listado-movimiento', [MovimientoVehController::class, 'listar']);
    Route::get('/eliminar-movimiento/{id}', [MovimientoVehController::class, 'eliminar']);

    //DESPACHO COMBUSTIBLE
    Route::get('/despacho-combustible', [DespachoCombustibleController::class, 'index']);
    Route::post('/guardar-cab-despacho', [DespachoCombustibleController::class, 'guardarCabecera']);
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

});