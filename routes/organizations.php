<?php

use Illuminate\Support\Facades\Route;
use Modules\Organization\Http\Controllers\OrganizationController;

Route::prefix('organizations')->name('organizations.')->middleware(['auth', 'module.access:organizations'])->group(function () {
    Route::get('/', [OrganizationController::class, 'index'])->name('index');
    Route::get('/create', [OrganizationController::class, 'create'])->name('create');
    Route::post('/', [OrganizationController::class, 'apiStore'])->name('store');
    Route::get('/{organization}', [OrganizationController::class, 'show'])->name('show');
    Route::get('/{organization}/edit', [OrganizationController::class, 'edit'])->name('edit');
    Route::put('/{organization}', [OrganizationController::class, 'apiUpdate'])->name('update');
    Route::patch('/{organization}', [OrganizationController::class, 'apiUpdate']);
    Route::delete('/{organization}', [OrganizationController::class, 'apiDestroy'])->name('destroy');
});
