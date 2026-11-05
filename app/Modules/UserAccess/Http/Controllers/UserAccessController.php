<?php

namespace Modules\UserAccess\Http\Controllers;

use App\Http\Controllers\Concerns\RendersModuleViews;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\UserAccess\Models\ModuleModel;
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
        // Mode bulk: form mengirim matriks access[module][permission].
        if ($request->boolean('access_mode')) {
            $data = $request->validate([
                'user_id' => 'required|exists:users,id',
                'access' => 'nullable|array',
                'access.*' => 'nullable|array',
                'access.*.*' => 'sometimes|boolean',
            ]);

            $matrix = $this->matrixFromInput($data['access'] ?? []);
            $lastAccess = $this->syncUserAccesses((int) $data['user_id'], $matrix, false);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Hak akses berhasil disimpan.',
                    'data' => $lastAccess ? new \Modules\UserAccess\Http\Resources\UserAccessResource($lastAccess->load('user')) : null,
                ], 201);
            }

            return redirect()->route('user-accesses.index')->with('success', 'Hak akses berhasil disimpan.');
        }

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'module' => 'required|string|max:100',
            'allow_create' => 'sometimes|boolean',
            'allow_edit' => 'sometimes|boolean',
            'allow_view' => 'sometimes|boolean',
            'allow_delete' => 'sometimes|boolean',
        ]);

        $values = [
            'allow_create' => $data['allow_create'] ?? false,
            'allow_edit' => $data['allow_edit'] ?? false,
            'allow_view' => $data['allow_view'] ?? false,
            'allow_delete' => $data['allow_delete'] ?? false,
        ];

        // Cari termasuk data yang sudah soft-deleted, karena unique index
        // (user_id, module) juga berlaku untuk baris yang terhapus.
        $access = UserAccessModel::withTrashed()
            ->where('user_id', $data['user_id'])
            ->where('module', $data['module'])
            ->first();

        if ($access) {
            if ($access->trashed()) {
                $access->restore();
            }

            $access->update($values);
        } else {
            $access = UserAccessModel::create([
                'user_id' => $data['user_id'],
                'module' => $data['module'],
                ...$values,
            ]);
        }

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

        // Mode bulk: update seluruh hak akses milik pengguna terkait.
        if ($request->boolean('access_mode')) {
            $data = $request->validate([
                'access' => 'nullable|array',
                'access.*' => 'nullable|array',
                'access.*.*' => 'sometimes|boolean',
            ]);

            $matrix = $this->matrixFromInput($data['access'] ?? []);
            $lastAccess = $this->syncUserAccesses($userAccess->user_id, $matrix);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Hak akses berhasil diperbarui.',
                    'data' => $lastAccess ? new \Modules\UserAccess\Http\Resources\UserAccessResource($lastAccess->load('user')) : null,
                ], 200);
            }

            return redirect()->route('user-accesses.index')->with('success', 'Hak akses berhasil diperbarui.');
        }

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
     * Ubah input access[module][permission] menjadi matriks boolean.
     *
     * @param  array<string, array<string, mixed>>  $input
     * @return array<string, array<string, bool>>
     */
    private function matrixFromInput(array $input): array
    {
        $matrix = [];

        foreach (ModuleModel::activeNames() as $module) {
            $permissions = $input[$module] ?? [];

            $matrix[$module] = [
                'allow_view' => (bool) ($permissions['view'] ?? false),
                'allow_create' => (bool) ($permissions['create'] ?? false),
                'allow_edit' => (bool) ($permissions['edit'] ?? false),
                'allow_delete' => (bool) ($permissions['delete'] ?? false),
            ];
        }

        return $matrix;
    }

    /**
     * Sinkronkan seluruh hak akses milik satu pengguna sekaligus.
     *
     * Modul tanpa permission akan di-soft-delete (jika $removeUnchecked).
     * Baris yang sudah soft-deleted akan di-restore agar tidak melanggar
     * unique index.
     */
    private function syncUserAccesses(int $userId, array $matrix, bool $removeUnchecked = true): ?UserAccessModel
    {
        return DB::transaction(function () use ($userId, $matrix, $removeUnchecked) {
            $lastAccess = null;

            foreach ($matrix as $module => $values) {
                $hasPermission = in_array(true, $values, true);

                $access = UserAccessModel::withTrashed()
                    ->where('user_id', $userId)
                    ->where('module', $module)
                    ->first();

                if (! $hasPermission) {
                    if ($removeUnchecked && $access && ! $access->trashed()) {
                        $access->delete();
                    }

                    continue;
                }

                if ($access) {
                    if ($access->trashed()) {
                        $access->restore();
                    }

                    $access->update($values);
                } else {
                    $access = UserAccessModel::create([
                        'user_id' => $userId,
                        'module' => $module,
                        ...$values,
                    ]);
                }

                $lastAccess = $access;
            }

            return $lastAccess;
        });
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
        $modules = ModuleModel::activeList()->get();

        return $this->renderPage('index', compact('accesses', 'modules'));
    }

    public function create()
    {
        $users = User::all();
        $modules = ModuleModel::activeList()->get();
        $accessByModule = collect();

        return $this->renderPage('create', compact('users', 'modules', 'accessByModule'));
    }

    public function show(Request $request, string $userAccess)
    {
        if ($request->expectsJson()) {
            return $this->apiShow($userAccess);
        }

        $access = UserAccessModel::with('user')->findOrFail($userAccess);
        $moduleLabel = ModuleModel::where('name', $access->module)->value('label') ?? $access->module;

        return $this->renderPage('show', compact('access', 'moduleLabel'));
    }

    public function edit(string $userAccess)
    {
        $access = UserAccessModel::with('user')->findOrFail($userAccess);
        $modules = ModuleModel::activeList()->get();
        $accessByModule = UserAccessModel::where('user_id', $access->user_id)
            ->get()
            ->keyBy('module');

        return $this->renderPage('edit', compact('access', 'modules', 'accessByModule'));
    }
}
