<?php

use Illuminate\Support\Facades\Route;
use Modules\Member\Http\Controllers\MemberController;

Route::prefix('members')->name('members.')->group(function () {
    Route::get('/', [MemberController::class, 'index'])->name('index');
    Route::get('/create', [MemberController::class, 'create'])->name('create');
    Route::post('/', [MemberController::class, 'apiStore'])->name('store');
    Route::get('/{member}', [MemberController::class, 'show'])->name('show');
    Route::get('/{member}/edit', [MemberController::class, 'edit'])->name('edit');
    Route::put('/{member}', [MemberController::class, 'apiUpdate'])->name('update');
    Route::patch('/{member}', [MemberController::class, 'apiUpdate']);
    Route::delete('/{member}', [MemberController::class, 'apiDestroy'])->name('destroy');
});
