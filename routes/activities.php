<?php

use Illuminate\Support\Facades\Route;
use Modules\Activity\Http\Controllers\ActivityController;

Route::prefix('activities')->name('activities.')->middleware(['auth', 'module.access:activities'])->group(function () {
    Route::get('/', [ActivityController::class, 'index'])->name('index');
    Route::get('/create', [ActivityController::class, 'create'])->name('create');
    Route::post('/', [ActivityController::class, 'apiStore'])->name('store');
    Route::get('/{activity}', [ActivityController::class, 'show'])->name('show');
    Route::get('/{activity}/edit', [ActivityController::class, 'edit'])->name('edit');
    Route::put('/{activity}', [ActivityController::class, 'apiUpdate'])->name('update');
    Route::patch('/{activity}', [ActivityController::class, 'apiUpdate']);
    Route::delete('/{activity}', [ActivityController::class, 'apiDestroy'])->name('destroy');
});
