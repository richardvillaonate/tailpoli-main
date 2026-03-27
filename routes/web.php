<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/carnet/{id}', function($id){

    $matricula = \App\Models\Academico\Matricula::find($id);

    $nombre = $matricula->alumno->documento."_carnet.pdf";

    return \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.carnet', compact('matricula','id'))
            ->download($nombre);

})->name('carnet.descargar');
