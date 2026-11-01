<?php

use Illuminate\Support\Facades\Route;
use Modules\Meeting\Http\Controllers\MeetingController;

Route::prefix('meetings')->name('meetings.')->group(function () {
    Route::get('/', [MeetingController::class, 'index'])->name('index');
    Route::get('/create', [MeetingController::class, 'create'])->name('create');
    Route::post('/', [MeetingController::class, 'apiStore'])->name('store');
    Route::get('/{meeting}', [MeetingController::class, 'show'])->name('show');
    Route::get('/{meeting}/edit', [MeetingController::class, 'edit'])->name('edit');
    Route::put('/{meeting}', [MeetingController::class, 'apiUpdate'])->name('update');
    Route::patch('/{meeting}', [MeetingController::class, 'apiUpdate']);
    Route::delete('/{meeting}', [MeetingController::class, 'apiDestroy'])->name('destroy');
});
