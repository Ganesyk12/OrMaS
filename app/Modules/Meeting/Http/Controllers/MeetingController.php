<?php

namespace Modules\Meeting\Http\Controllers;

use App\Http\Controllers\Concerns\RendersModuleViews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Meeting\Models\MeetingModel;

class MeetingController extends Controller
{
    use RendersModuleViews;

    protected string $viewModule = 'meetings';

    public function apiIndex(Request $request)
    {
        $query = MeetingModel::query()->with('organization');

        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        return \Modules\Meeting\Http\Resources\MeetingResource::collection($query->paginate($request->get('per_page', 15)))
            ->response()
            ->setStatusCode(200);
    }

    public function apiStore(Request $request)
    {
        $data = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
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
                'data' => new \Modules\Meeting\Http\Resources\MeetingResource($meeting->load('organization')),
            ], 201);
        }

        return redirect()->route('meetings.index')->with('success', 'Rapat berhasil dijadwalkan.');
    }

    public function apiShow(string $meeting)
    {
        $meeting = MeetingModel::findOrFail($meeting);

        return response()->json([
            'message' => 'Data rapat.',
            'data' => new \Modules\Meeting\Http\Resources\MeetingResource($meeting->load(['organization', 'agendas'])),
        ], 200);
    }

    public function apiUpdate(Request $request, string $meeting)
    {
        $meeting = MeetingModel::findOrFail($meeting);

        $data = $request->validate([
            'organization_id' => 'sometimes|exists:organizations,id',
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
                'data' => new \Modules\Meeting\Http\Resources\MeetingResource($meeting->fresh()->load('organization')),
            ], 200);
        }

        return redirect()->route('meetings.index')->with('success', 'Rapat berhasil diperbarui.');
    }

    public function apiDestroy(Request $request, string $meeting)
    {
        $meeting = MeetingModel::findOrFail($meeting);
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

        $query = MeetingModel::query()->with('organization');

        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        $meetings = $query->paginate(15)->withQueryString();
        $organizations = \Modules\Organization\Models\OrganizationModel::active()->get();

        return $this->renderPage('index', compact('meetings', 'organizations'));
    }

    public function create()
    {
        $organizations = \Modules\Organization\Models\OrganizationModel::active()->get();

        return $this->renderPage('create', compact('organizations'));
    }

    public function show(Request $request, string $meeting)
    {
        if ($request->expectsJson()) {
            return $this->apiShow($meeting);
        }

        $meeting = MeetingModel::with(['organization', 'agendas'])->findOrFail($meeting);

        return $this->renderPage('show', compact('meeting'));
    }

    public function edit(string $meeting)
    {
        $meeting = MeetingModel::findOrFail($meeting);
        $organizations = \Modules\Organization\Models\OrganizationModel::active()->get();

        return $this->renderPage('edit', compact('meeting', 'organizations'));
    }
}
