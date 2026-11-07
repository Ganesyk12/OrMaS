<?php

namespace Modules\Meeting\Http\Controllers;

use App\Http\Controllers\Concerns\RendersModuleViews;
use App\Http\Controllers\Controller;
use App\Rules\AccessibleOrganization;
use Illuminate\Http\Request;
use Modules\Meeting\Http\Resources\MeetingResource;
use Modules\Meeting\Models\MeetingModel;
use Modules\Organization\Models\OrganizationModel;

class MeetingController extends Controller
{
    use RendersModuleViews;

    protected string $viewModule = 'meetings';

    public function apiIndex(Request $request)
    {
        $query = MeetingModel::query()
            ->with('organization')
            ->visibleToUser($request->user());

        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        return MeetingResource::collection($query->paginate($request->get('per_page', 15)))
            ->response()
            ->setStatusCode(200);
    }

    public function apiStore(Request $request)
    {
        $data = $request->validate([
            'organization_id' => ['required', new AccessibleOrganization($request->user())],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'status' => 'sometimes|in:scheduled,completed,cancelled',
        ]);

        $meeting = MeetingModel::create(array_merge($data, [
            'created_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Rapat berhasil dijadwalkan.',
                'data' => new MeetingResource($meeting->load('organization')),
            ], 201);
        }

        return redirect()->route('meetings.index')->with('success', 'Rapat berhasil dijadwalkan.');
    }

    public function apiShow(Request $request, string $meeting)
    {
        $meeting = MeetingModel::query()
            ->visibleToUser($request->user())
            ->findOrFail($meeting);

        return response()->json([
            'message' => 'Data rapat.',
            'data' => new MeetingResource($meeting->load('organization')),
        ], 200);
    }

    public function apiUpdate(Request $request, string $meeting)
    {
        $meeting = MeetingModel::query()
            ->visibleToUser($request->user())
            ->findOrFail($meeting);

        $data = $request->validate([
            'organization_id' => ['sometimes', new AccessibleOrganization($request->user())],
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'meeting_date' => 'sometimes|date',
            'location' => 'nullable|string|max:255',
            'status' => 'sometimes|in:scheduled,completed,cancelled',
        ]);

        $meeting->update(array_merge($data, [
            'updated_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Rapat berhasil diperbarui.',
                'data' => new MeetingResource($meeting->fresh()->load('organization')),
            ], 200);
        }

        return redirect()->route('meetings.index')->with('success', 'Rapat berhasil diperbarui.');
    }

    public function apiDestroy(Request $request, string $meeting)
    {
        $meeting = MeetingModel::query()
            ->visibleToUser($request->user())
            ->findOrFail($meeting);
        $meeting->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Rapat berhasil dihapus.',
            ], 200);
        }

        return redirect()->route('meetings.index')->with('success', 'Rapat berhasil dihapus.');
    }

    // === VIEW METHODS ===

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            return $this->apiIndex($request);
        }

        $query = MeetingModel::query()
            ->with('organization')
            ->visibleToUser($request->user());

        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        $meetings = $query->paginate(15)->withQueryString();
        $organizations = OrganizationModel::active()
            ->visibleTo($request->user())
            ->orderBy('name')
            ->get();

        return $this->renderPage('index', compact('meetings', 'organizations'));
    }

    public function create(Request $request)
    {
        $organizations = OrganizationModel::active()
            ->visibleTo($request->user())
            ->orderBy('name')
            ->get();

        return $this->renderPage('create', compact('organizations'));
    }

    public function show(Request $request, string $meeting)
    {
        if ($request->expectsJson()) {
            return $this->apiShow($request, $meeting);
        }

        $meeting = MeetingModel::query()
            ->with('organization')
            ->visibleToUser($request->user())
            ->findOrFail($meeting);

        return $this->renderPage('show', compact('meeting'));
    }

    public function edit(Request $request, string $meeting)
    {
        $meeting = MeetingModel::query()
            ->visibleToUser($request->user())
            ->findOrFail($meeting);
        $organizations = OrganizationModel::active()
            ->visibleTo($request->user())
            ->orderBy('name')
            ->get();

        return $this->renderPage('edit', compact('meeting', 'organizations'));
    }
}
