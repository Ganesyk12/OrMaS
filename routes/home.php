<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.index');

Route::get('/docs', function () {
    abort_unless(auth()->user()?->isSuperAdmin(), 403);

    return response()->file(resource_path('docs/index.html'));
})->middleware('auth')->name('docs.index');
