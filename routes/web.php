<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/registro-paciente', [App\Http\Controllers\HomeController::class, 'test']);
Route::get('/obtener-canton-prov/{idprov}', [App\Http\Controllers\HomeController::class, 'obtenerCantones']);
Route::get('/obtener-parroquia-canton/{idcanton}', [App\Http\Controllers\HomeController::class, 'obtenerParroquias']);
Route::post('/guardar-paciente', [App\Http\Controllers\HomeController::class, 'guardar']);
Route::get('/busqueda', [App\Http\Controllers\HomeController::class, 'busqueda'])->name('home');
Route::get('/buscarPaciente', [App\Http\Controllers\HomeController::class, 'busquedaPaciente']);
Route::get('/info-paciente/{idpac}', [App\Http\Controllers\HomeController::class, 'infoPaciente']);
Route::put('/actualiza-paciente/{idpac}', [App\Http\Controllers\HomeController::class, 'actualiza']);



