<?php

use Illuminate\Support\Facades\Route;
use Modules\Letter\Http\Controllers\LetterController;

Route::prefix('letters')->name('letters.')->middleware(['auth', 'module.access:letters'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('letters.index', ['direction' => 'incoming']);
    })->name('home');

    Route::get('/{direction}', [LetterController::class, 'index'])
        ->whereIn('direction', ['incoming', 'outgoing'])
        ->name('index');
    Route::get('/{direction}/create', [LetterController::class, 'create'])
        ->whereIn('direction', ['incoming', 'outgoing'])
        ->name('create');
    Route::post('/{direction}', [LetterController::class, 'apiStore'])
        ->whereIn('direction', ['incoming', 'outgoing'])
        ->name('store');
    Route::get('/{direction}/{letter}', [LetterController::class, 'show'])
        ->whereIn('direction', ['incoming', 'outgoing'])
        ->name('show');
    Route::get('/{direction}/{letter}/edit', [LetterController::class, 'edit'])
        ->whereIn('direction', ['incoming', 'outgoing'])
        ->name('edit');
    Route::put('/{direction}/{letter}', [LetterController::class, 'apiUpdate'])
        ->whereIn('direction', ['incoming', 'outgoing'])
        ->name('update');
    Route::patch('/{direction}/{letter}', [LetterController::class, 'apiUpdate'])
        ->whereIn('direction', ['incoming', 'outgoing']);
    Route::delete('/{direction}/{letter}', [LetterController::class, 'apiDestroy'])
        ->whereIn('direction', ['incoming', 'outgoing'])
        ->name('destroy');
});
