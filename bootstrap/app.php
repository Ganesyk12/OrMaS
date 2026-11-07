<?php

use App\Http\Middleware\EnsureModuleAccess;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'module.access' => EnsureModuleAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Tangani token CSRF kedaluwarsa tanpa halaman "419 Page Expired".
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() !== 419) {
                return null;
            }

            $message = 'Sesi halaman sudah kedaluwarsa. Silakan muat ulang halaman lalu coba lagi.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 419);
            }

            return redirect()->route('login', ['expired' => 1]);
        });
    })->create();
