<?php

namespace Modules\Role\Http\Controllers;

use App\Http\Controllers\Concerns\RendersModuleViews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Role\Models\RoleModel;

class RoleController extends Controller
{
    use RendersModuleViews;

    protected string $viewModule = 'roles';

    public function apiIndex(Request $request)
    {
        $query = RoleModel::query()->active();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('code', 'like', '%'.$request->search.'%');
        }

        return \Modules\Role\Http\Resources\RoleResource::collection($query->paginate($request->get('per_page', 15)))
            ->response()
            ->setStatusCode(200);
    }

    public function apiStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:roles',
            'description' => 'nullable|string',
            'level' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $role = RoleModel::create(array_merge($data, [
            'created_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Role berhasil dibuat.',
                'data' => new \Modules\Role\Http\Resources\RoleResource($role),
            ], 201);
        }

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function apiShow(string $role)
    {
        $role = RoleModel::findOrFail($role);

        return response()->json([
            'message' => 'Data role.',
            'data' => new \Modules\Role\Http\Resources\RoleResource($role->load('members')),
        ], 200);
    }

    public function apiUpdate(Request $request, string $role)
    {
        $role = RoleModel::findOrFail($role);

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:roles,code,'.$role->id,
            'description' => 'nullable|string',
            'level' => 'sometimes|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $role->update(array_merge($data, [
            'updated_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Role berhasil diperbarui.',
                'data' => new \Modules\Role\Http\Resources\RoleResource($role->fresh()),
            ], 200);
        }

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function apiDestroy(Request $request, string $role)
    {
        $role = RoleModel::findOrFail($role);
        $role->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Role berhasil dihapus.',
            ], 200);
        }

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }

    // === VIEW METHODS ===

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            return $this->apiIndex($request);
        }

        $query = RoleModel::query()->active();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('code', 'like', '%'.$request->search.'%');
        }

        $roles = $query->paginate(15)->withQueryString();

        return $this->renderPage('index', compact('roles'));
    }

    public function create()
    {
        return $this->renderPage('create');
    }

    public function show(Request $request, string $role)
    {
        if ($request->expectsJson()) {
            return $this->apiShow($role);
        }

        $role = RoleModel::with('members.user')->findOrFail($role);

        return $this->renderPage('show', compact('role'));
    }

    public function edit(string $role)
    {
        $role = RoleModel::findOrFail($role);

        return $this->renderPage('edit', compact('role'));
    }
}
