<?php

namespace Modules\Activity\Http\Controllers;

use App\Http\Controllers\Concerns\RendersModuleViews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Activity\Models\ActivityModel;

class ActivityController extends Controller
{
    use RendersModuleViews;

    protected string $viewModule = 'activities';

    public function apiIndex(Request $request)
    {
        $query = ActivityModel::query()->with('organization');

        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->filled('status')) {
            $query->status($request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return \Modules\Activity\Http\Resources\ActivityResource::collection($query->paginate($request->get('per_page', 15)))
            ->response()
            ->setStatusCode(200);
    }

    public function apiStore(Request $request)
    {
        $data = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:program,kegiatan,pelatihan,sosial',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'sometimes|in:planning,ongoing,completed,cancelled',
        ]);

        $activity = ActivityModel::create(array_merge($data, [
            'created_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Kegiatan berhasil dibuat.',
                'data' => new \Modules\Activity\Http\Resources\ActivityResource($activity->load('organization')),
            ], 201);
        }

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil dibuat.');
    }

    public function apiShow(string $activity)
    {
        $activity = ActivityModel::findOrFail($activity);

        return response()->json([
            'message' => 'Data kegiatan.',
            'data' => new \Modules\Activity\Http\Resources\ActivityResource($activity->load('organization')),
        ], 200);
    }

    public function apiUpdate(Request $request, string $activity)
    {
        $activity = ActivityModel::findOrFail($activity);

        $data = $request->validate([
            'organization_id' => 'sometimes|exists:organizations,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|in:program,kegiatan,pelatihan,sosial',
            'start_date' => 'sometimes|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'sometimes|in:planning,ongoing,completed,cancelled',
        ]);

        $activity->update(array_merge($data, [
            'updated_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Kegiatan berhasil diperbarui.',
                'data' => new \Modules\Activity\Http\Resources\ActivityResource($activity->fresh()->load('organization')),
            ], 200);
        }

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function apiDestroy(Request $request, string $activity)
    {
        $activity = ActivityModel::findOrFail($activity);
        $activity->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Kegiatan berhasil dihapus.',
            ], 200);
        }

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil dihapus.');
    }

    // === VIEW METHODS ===

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            return $this->apiIndex($request);
        }

        $query = ActivityModel::query()->with('organization');

        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        $activities = $query->paginate(15)->withQueryString();
        $organizations = \Modules\Organization\Models\OrganizationModel::active()->get();

        return $this->renderPage('index', compact('activities', 'organizations'));
    }

    public function create()
    {
        $organizations = \Modules\Organization\Models\OrganizationModel::active()->get();

        return $this->renderPage('create', compact('organizations'));
    }

    public function show(Request $request, string $activity)
    {
        if ($request->expectsJson()) {
            return $this->apiShow($activity);
        }

        $activity = ActivityModel::with('organization')->findOrFail($activity);

        return $this->renderPage('show', compact('activity'));
    }

    public function edit(string $activity)
    {
        $activity = ActivityModel::findOrFail($activity);
        $organizations = \Modules\Organization\Models\OrganizationModel::active()->get();

        return $this->renderPage('edit', compact('activity', 'organizations'));
    }
}
