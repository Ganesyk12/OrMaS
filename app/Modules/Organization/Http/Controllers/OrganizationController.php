<?php

namespace Modules\Organization\Http\Controllers;

use App\Http\Controllers\Concerns\RendersModuleViews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Organization\Models\OrganizationModel;
use Modules\Shared\Exceptions\ModuleException;

class OrganizationController extends Controller
{
    use RendersModuleViews;

    protected string $viewModule = 'organizations';

    public function apiIndex(Request $request)
    {
        $query = OrganizationModel::query()->active();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            $query->type($request->type);
        }

        return \Modules\Organization\Http\Resources\OrganizationResource::collection($query->paginate($request->get('per_page', 15)))
            ->response()
            ->setStatusCode(200);
    }

    public function apiStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',

            'type' => 'required|string',
            'level' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'founded_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $organization = OrganizationModel::create(array_merge($data, [
            'created_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Organisasi berhasil dibuat.',
                'data' => new \Modules\Organization\Http\Resources\OrganizationResource($organization),
            ], 201);
        }

        return redirect()->route('organizations.index')->with('success', 'Organisasi berhasil dibuat.');
    }

    public function apiShow(string $organization)
    {
        $organization = OrganizationModel::findOrFail($organization);

        return response()->json([
            'message' => 'Data organisasi.',
            'data' => new \Modules\Organization\Http\Resources\OrganizationResource($organization->load('members')),
        ], 200);
    }

    public function apiUpdate(Request $request, string $organization)
    {
        $organization = OrganizationModel::findOrFail($organization);

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',

            'type' => 'required|string',
            'level' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'founded_at' => 'nullable|date',
            'is_active' => 'sometimes|boolean',
        ]);

        $organization->update(array_merge($data, [
            'updated_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Organisasi berhasil diperbarui.',
                'data' => new \Modules\Organization\Http\Resources\OrganizationResource($organization->fresh()),
            ], 200);
        }

        return redirect()->route('organizations.index')->with('success', 'Organisasi berhasil diperbarui.');
    }

    public function apiDestroy(Request $request, string $organization)
    {
        $organization = OrganizationModel::findOrFail($organization);
        $organization->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Organisasi berhasil dihapus.',
            ], 200);
        }

        return redirect()->route('organizations.index')->with('success', 'Organisasi berhasil dihapus.');
    }

    // === VIEW METHODS ===

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            return $this->apiIndex($request);
        }

        $query = OrganizationModel::query()->active();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $organizations = $query->paginate(15)->withQueryString();

        return $this->renderPage('index', compact('organizations'));
    }

    public function create()
    {
        return $this->renderPage('create');
    }

    public function show(Request $request, string $organization)
    {
        if ($request->expectsJson()) {
            return $this->apiShow($organization);
        }

        $organization = OrganizationModel::with('members.user', 'members.role')->findOrFail($organization);

        return $this->renderPage('show', compact('organization'));
    }

    public function edit(string $organization)
    {
        $organization = OrganizationModel::findOrFail($organization);

        return $this->renderPage('edit', compact('organization'));
    }
}
