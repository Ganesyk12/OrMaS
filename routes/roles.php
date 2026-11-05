<?php

use Illuminate\Support\Facades\Route;
use Modules\Role\Http\Controllers\RoleController;

Route::prefix('roles')->name('roles.')->middleware(['auth', 'module.access:roles'])->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('/create', [RoleController::class, 'create'])->name('create');
    Route::post('/', [RoleController::class, 'apiStore'])->name('store');
    Route::get('/{role}', [RoleController::class, 'show'])->name('show');
    Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
    Route::put('/{role}', [RoleController::class, 'apiUpdate'])->name('update');
    Route::patch('/{role}', [RoleController::class, 'apiUpdate']);
    Route::delete('/{role}', [RoleController::class, 'apiDestroy'])->name('destroy');
});
