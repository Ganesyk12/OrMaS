<?php

namespace Modules\Member\Http\Controllers;

use App\Http\Controllers\Concerns\RendersModuleViews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Member\Models\OrganizationMemberModel;

class MemberController extends Controller
{
    use RendersModuleViews;

    protected string $viewModule = 'members';

    public function apiIndex(Request $request)
    {
        $query = OrganizationMemberModel::query()->active()->with(['organization', 'user', 'role']);

        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }
        if ($request->filled('search')) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
                ->orWhere('member_number', 'like', '%'.$request->search.'%');
        }

        return \Modules\Member\Http\Resources\MemberResource::collection($query->paginate($request->get('per_page', 15)))
            ->response()
            ->setStatusCode(200);
    }

    public function apiStore(Request $request)
    {
        $data = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
            'member_number' => 'required|string|max:50|unique:organization_members',
            'join_date' => 'required|date',
            'is_active' => 'boolean',
        ]);

        $member = OrganizationMemberModel::create(array_merge($data, [
            'created_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Anggota berhasil ditambahkan.',
                'data' => new \Modules\Member\Http\Resources\MemberResource($member->load(['organization', 'user', 'role'])),
            ], 201);
        }

        return redirect()->route('members.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function apiShow(string $member)
    {
        $member = OrganizationMemberModel::findOrFail($member);

        return response()->json([
            'message' => 'Data anggota.',
            'data' => new \Modules\Member\Http\Resources\MemberResource($member->load(['organization', 'user', 'role'])),
        ], 200);
    }

    public function apiUpdate(Request $request, string $member)
    {
        $member = OrganizationMemberModel::findOrFail($member);

        $data = $request->validate([
            'organization_id' => 'sometimes|exists:organizations,id',
            'user_id' => 'sometimes|exists:users,id',
            'role_id' => 'sometimes|exists:roles,id',
            'member_number' => 'sometimes|string|max:50|unique:organization_members,member_number,'.$member->id,
            'join_date' => 'sometimes|date',
            'is_active' => 'sometimes|boolean',
        ]);

        $member->update(array_merge($data, [
            'updated_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Anggota berhasil diperbarui.',
                'data' => new \Modules\Member\Http\Resources\MemberResource($member->fresh()->load(['organization', 'user', 'role'])),
            ], 200);
        }

        return redirect()->route('members.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    public function apiDestroy(Request $request, string $member)
    {
        $member = OrganizationMemberModel::findOrFail($member);
        $member->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Anggota berhasil dihapus.',
            ], 200);
        }

        return redirect()->route('members.index')->with('success', 'Anggota berhasil dihapus.');
    }

    // === VIEW METHODS ===

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            return $this->apiIndex($request);
        }

        $query = OrganizationMemberModel::query()->active()->with(['organization', 'user', 'role']);

        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->filled('search')) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
                ->orWhere('member_number', 'like', '%'.$request->search.'%');
        }

        $members = $query->paginate(15)->withQueryString();
        $organizations = \Modules\Organization\Models\OrganizationModel::active()->get();

        return $this->renderPage('index', compact('members', 'organizations'));
    }

    public function create()
    {
        $organizations = \Modules\Organization\Models\OrganizationModel::active()->get();
        $roles = \Modules\Role\Models\RoleModel::active()->get();
        $users = \App\Models\User::all();

        return $this->renderPage('create', compact('organizations', 'roles', 'users'));
    }

    public function show(Request $request, string $member)
    {
        if ($request->expectsJson()) {
            return $this->apiShow($member);
        }

        $member = OrganizationMemberModel::with(['organization', 'user', 'role'])->findOrFail($member);

        return $this->renderPage('show', compact('member'));
    }

    public function edit(string $member)
    {
        $member = OrganizationMemberModel::findOrFail($member);
        $organizations = \Modules\Organization\Models\OrganizationModel::active()->get();
        $roles = \Modules\Role\Models\RoleModel::active()->get();
        $users = \App\Models\User::all();

        return $this->renderPage('edit', compact('member', 'organizations', 'roles', 'users'));
    }
}
