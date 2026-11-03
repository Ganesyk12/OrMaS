<?php

use Illuminate\Support\Facades\Route;
use Modules\LetterCategory\Http\Controllers\LetterCategoryController;

Route::prefix('letter-categories')->name('letter-categories.')->group(function () {
    Route::get('/', [LetterCategoryController::class, 'index'])->name('index');
    Route::get('/create', [LetterCategoryController::class, 'create'])->name('create');
    Route::post('/', [LetterCategoryController::class, 'apiStore'])->name('store');
    Route::get('/{letterCategory}', [LetterCategoryController::class, 'show'])->name('show');
    Route::get('/{letterCategory}/edit', [LetterCategoryController::class, 'edit'])->name('edit');
    Route::put('/{letterCategory}', [LetterCategoryController::class, 'apiUpdate'])->name('update');
    Route::patch('/{letterCategory}', [LetterCategoryController::class, 'apiUpdate']);
    Route::delete('/{letterCategory}', [LetterCategoryController::class, 'apiDestroy'])->name('destroy');
});
