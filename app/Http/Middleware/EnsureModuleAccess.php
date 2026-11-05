<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\UserAccess\Models\UserAccessModel;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleAccess
{
    /**
     * Pastikan user yang login punya permission pada modul terkait.
     *
     * Pemakaian: ->middleware('module.access:organizations')
     * Action ditentukan otomatis dari nama route / HTTP method:
     * index|show => view, create|store => create, edit|update => edit, destroy => delete.
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = $request->user();

        if (! $user) {
            if ($request->expectsJson()) {
                abort(401, 'Silakan login terlebih dahulu.');
            }

            return redirect()->route('login');
        }

        $action = $this->resolveAction($request);

        if (! UserAccessModel::checkAllowed($user, $module, $action)) {
            abort(403, "Anda tidak memiliki hak akses {$action} pada modul {$module}.");
        }

        return $next($request);
    }

    /**
     * Tentukan action dari nama route, fallback ke HTTP method.
     */
    private function resolveAction(Request $request): string
    {
        $routeName = $request->route()?->getName();

        if ($routeName) {
            $action = last(explode('.', $routeName));

            $mapped = match ($action) {
                'index', 'show', 'home' => 'view',
                'create', 'store' => 'create',
                'edit', 'update' => 'edit',
                'destroy' => 'delete',
                default => null,
            };

            if ($mapped) {
                return $mapped;
            }
        }

        return match ($request->method()) {
            'POST' => 'create',
            'PUT', 'PATCH' => 'edit',
            'DELETE' => 'delete',
            default => 'view',
        };
    }
}
