<?php

use Illuminate\Support\Facades\Route;
use Modules\UserAccess\Http\Controllers\UserAccessController;

Route::prefix('user-accesses')->name('user-accesses.')->middleware(['auth', 'module.access:user-accesses'])->group(function () {
    Route::get('/', [UserAccessController::class, 'index'])->name('index');
    Route::get('/create', [UserAccessController::class, 'create'])->name('create');
    Route::post('/', [UserAccessController::class, 'apiStore'])->name('store');
    Route::get('/{userAccess}', [UserAccessController::class, 'show'])->name('show');
    Route::get('/{userAccess}/edit', [UserAccessController::class, 'edit'])->name('edit');
    Route::put('/{userAccess}', [UserAccessController::class, 'apiUpdate'])->name('update');
    Route::patch('/{userAccess}', [UserAccessController::class, 'apiUpdate']);
    Route::delete('/{userAccess}', [UserAccessController::class, 'apiDestroy'])->name('destroy');
});
