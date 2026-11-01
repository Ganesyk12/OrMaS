<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Concerns\RendersModuleViews;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use RendersModuleViews;

    protected string $viewModule = 'users';

    public function apiIndex(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%');
        }

        return \Modules\User\Http\Resources\UserResource::collection($query->paginate($request->get('per_page', 15)))
            ->response()
            ->setStatusCode(200);
    }

    public function apiStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create(array_merge($data, [
            'password' => bcrypt($data['password']),
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Pengguna berhasil dibuat.',
                'data' => new \Modules\User\Http\Resources\UserResource($user),
            ], 201);
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    public function apiShow(string $user)
    {
        $user = User::findOrFail($user);

        return response()->json([
            'message' => 'Data pengguna.',
            'data' => new \Modules\User\Http\Resources\UserResource($user),
        ], 200);
    }

    public function apiUpdate(Request $request, string $user)
    {
        $user = User::findOrFail($user);

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:8',
        ]);

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Pengguna berhasil diperbarui.',
                'data' => new \Modules\User\Http\Resources\UserResource($user->fresh()),
            ], 200);
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function apiDestroy(Request $request, string $user)
    {
        $user = User::findOrFail($user);
        $user->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Pengguna berhasil dihapus.',
            ], 200);
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    // === VIEW METHODS ===

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            return $this->apiIndex($request);
        }

        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%');
        }

        $users = $query->paginate(15)->withQueryString();

        return $this->renderPage('index', compact('users'));
    }

    public function create()
    {
        return $this->renderPage('create');
    }

    public function show(Request $request, string $user)
    {
        if ($request->expectsJson()) {
            return $this->apiShow($user);
        }

        $user = User::findOrFail($user);

        return $this->renderPage('show', compact('user'));
    }

    public function edit(string $user)
    {
        $user = User::findOrFail($user);

        return $this->renderPage('edit', compact('user'));
    }
}
