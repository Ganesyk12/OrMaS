<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\View;

trait RendersModuleViews
{
    /**
     * Render a module view using a single, shared function.
     *
     * Contoh: $this->renderPage('index', compact('organizations'));
     * akan me-render view "organizations.index".
     */
    protected function renderPage(string $page, array $data = [])
    {
        $module = $this->viewModule ?? null;

        abort_if(
            blank($module),
            500,
            'Properti $viewModule belum didefinisikan pada controller.'
        );

        $view = $module.'.'.$page;

        abort_unless(
            View::exists($view),
            404,
            "View [{$view}] belum tersedia."
        );

        return view($view, $data);
    }
}
