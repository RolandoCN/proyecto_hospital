<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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



