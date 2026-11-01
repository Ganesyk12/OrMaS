<?php

use Illuminate\Support\Facades\Route;
use Modules\Agenda\Http\Controllers\AgendaController;

Route::prefix('meetings/{meeting}/agendas')->name('agendas.')->group(function () {
    Route::get('/', [AgendaController::class, 'index'])->name('index');
    Route::get('/create', [AgendaController::class, 'create'])->name('create');
    Route::post('/', [AgendaController::class, 'apiStore'])->name('store');
    Route::get('/{agenda}', [AgendaController::class, 'show'])->name('show');
    Route::get('/{agenda}/edit', [AgendaController::class, 'edit'])->name('edit');
    Route::put('/{agenda}', [AgendaController::class, 'apiUpdate'])->name('update');
    Route::patch('/{agenda}', [AgendaController::class, 'apiUpdate']);
    Route::delete('/{agenda}', [AgendaController::class, 'apiDestroy'])->name('destroy');
});
