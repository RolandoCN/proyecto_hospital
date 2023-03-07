<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\VehiculosCombustible\VehiculoController;
use App\Http\Controllers\VehiculosCombustible\TareasController;
use App\Http\Controllers\VehiculosCombustible\MovimientoVehController;
use App\Http\Controllers\VehiculosCombustible\DespachoCombustibleController;


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Auth::routes();

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

//VEHICULOS
Route::get('/registro-vehiculo', [VehiculoController::class, 'index']);
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




