<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\FinanceController;

Route::prefix('finances')->name('finances.')->group(function () {
    Route::get('/', [FinanceController::class, 'index'])->name('index');
    Route::get('/create', [FinanceController::class, 'create'])->name('create');
    Route::post('/', [FinanceController::class, 'apiStore'])->name('store');
    Route::get('/{finance}', [FinanceController::class, 'show'])->name('show');
    Route::get('/{finance}/edit', [FinanceController::class, 'edit'])->name('edit');
    Route::put('/{finance}', [FinanceController::class, 'apiUpdate'])->name('update');
    Route::patch('/{finance}', [FinanceController::class, 'apiUpdate']);
    Route::delete('/{finance}', [FinanceController::class, 'apiDestroy'])->name('destroy');
});
