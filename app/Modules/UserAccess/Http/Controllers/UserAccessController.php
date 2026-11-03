<?php

namespace Modules\UserAccess\Http\Controllers;

use App\Http\Controllers\Concerns\RendersModuleViews;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\UserAccess\Models\UserAccessModel;

class UserAccessController extends Controller
{
    use RendersModuleViews;

    protected string $viewModule = 'user-accesses';

    public function apiIndex(Request $request)
    {
        $query = UserAccessModel::query()->with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        return \Modules\UserAccess\Http\Resources\UserAccessResource::collection($query->paginate($request->get('per_page', 15)))
            ->response()
            ->setStatusCode(200);
    }

    public function apiStore(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'module' => 'required|string|max:100',
            'allow_create' => 'sometimes|boolean',
            'allow_edit' => 'sometimes|boolean',
            'allow_view' => 'sometimes|boolean',
            'allow_delete' => 'sometimes|boolean',
        ]);

        $access = UserAccessModel::updateOrCreate(
            ['user_id' => $data['user_id'], 'module' => $data['module']],
            [
                'allow_create' => $data['allow_create'] ?? false,
                'allow_edit' => $data['allow_edit'] ?? false,
                'allow_view' => $data['allow_view'] ?? false,
                'allow_delete' => $data['allow_delete'] ?? false,
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Hak akses berhasil disimpan.',
                'data' => new \Modules\UserAccess\Http\Resources\UserAccessResource($access->load('user')),
            ], 201);
        }

        return redirect()->route('user-accesses.index')->with('success', 'Hak akses berhasil disimpan.');
    }

    public function apiShow(string $userAccess)
    {
        $userAccess = UserAccessModel::findOrFail($userAccess);

        return response()->json([
            'message' => 'Data hak akses.',
            'data' => new \Modules\UserAccess\Http\Resources\UserAccessResource($userAccess->load('user')),
        ], 200);
    }

    public function apiUpdate(Request $request, string $userAccess)
    {
        $userAccess = UserAccessModel::findOrFail($userAccess);

        $data = $request->validate([
            'allow_create' => 'sometimes|boolean',
            'allow_edit' => 'sometimes|boolean',
            'allow_view' => 'sometimes|boolean',
            'allow_delete' => 'sometimes|boolean',
        ]);

        $userAccess->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Hak akses berhasil diperbarui.',
                'data' => new \Modules\UserAccess\Http\Resources\UserAccessResource($userAccess->fresh()->load('user')),
            ], 200);
        }

        return redirect()->route('user-accesses.index')->with('success', 'Hak akses berhasil diperbarui.');
    }

    public function apiDestroy(Request $request, string $userAccess)
    {
        $userAccess = UserAccessModel::findOrFail($userAccess);
        $userAccess->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Hak akses berhasil dihapus.',
            ], 200);
        }

        return redirect()->route('user-accesses.index')->with('success', 'Hak akses berhasil dihapus.');
    }

    /**
     * Cek hak akses user untuk module & permission tertentu.
     */
    public function check(Request $request, User $user)
    {
        $module = $request->string('module');
        $action = $request->string('action'); // create, edit, view, delete

        $allowed = UserAccessModel::checkAllowed($user, $module, $action);

        return response()->json([
            'message' => 'Hasil cek hak akses.',
            'data' => [
                'user_id' => $user->id,
                'module' => $module,
                'action' => $action,
                'allowed' => $allowed,
            ],
        ], 200);
    }

    // === VIEW METHODS ===

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            return $this->apiIndex($request);
        }

        $query = UserAccessModel::query()->with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        $accesses = $query->paginate(15)->withQueryString();
        $users = \App\Models\User::all();

        return $this->renderPage('index', compact('accesses', 'users'));
    }

    public function create()
    {
        $users = \App\Models\User::all();

        return $this->renderPage('create', compact('users'));
    }

    public function show(Request $request, string $userAccess)
    {
        if ($request->expectsJson()) {
            return $this->apiShow($userAccess);
        }

        $access = UserAccessModel::with('user')->findOrFail($userAccess);

        return $this->renderPage('show', compact('access'));
    }

    public function edit(string $userAccess)
    {
        $access = UserAccessModel::findOrFail($userAccess);
        $users = \App\Models\User::all();

        return $this->renderPage('edit', compact('access', 'users'));
    }
}
