<?php

namespace Modules\Agenda\Http\Controllers;

use App\Http\Controllers\Concerns\RendersModuleViews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Agenda\Models\AgendaModel;
use Modules\Meeting\Models\MeetingModel;

class AgendaController extends Controller
{
    use RendersModuleViews;

    protected string $viewModule = 'agendas';

    public function apiIndex(Request $request, string $meeting)
    {
        $meeting = MeetingModel::findOrFail($meeting);

        return \Modules\Agenda\Http\Resources\AgendaResource::collection($meeting->agendas()->ordered()->paginate($request->get('per_page', 15)))
            ->response()
            ->setStatusCode(200);
    }

    public function apiStore(Request $request, string $meeting)
    {
        $meeting = MeetingModel::findOrFail($meeting);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'presenter' => 'nullable|string|max:255',
            'duration_minutes' => 'nullable|integer|min:1',
            'order' => 'nullable|integer|min:0',
            'status' => 'sometimes|in:pending,discussed,completed,skipped',
        ]);

        $agenda = $meeting->agendas()->create(array_merge($data, [
            'status' => $data['status'] ?? 'draft',
            'created_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Agenda rapat berhasil dibuat.',
                'data' => new \Modules\Agenda\Http\Resources\AgendaResource($agenda),
            ], 201);
        }

        return redirect()->route('agendas.index', $meeting->id)->with('success', 'Agenda rapat berhasil dibuat.');
    }

    public function apiShow(string $meeting, string $agenda)
    {
        $meeting = MeetingModel::findOrFail($meeting);
        $agenda = AgendaModel::findOrFail($agenda);

        return response()->json([
            'message' => 'Data agenda.',
            'data' => new \Modules\Agenda\Http\Resources\AgendaResource($agenda),
        ], 200);
    }

    public function apiUpdate(Request $request, string $meeting, string $agenda)
    {
        $meeting = MeetingModel::findOrFail($meeting);
        $agenda = AgendaModel::findOrFail($agenda);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'presenter' => 'nullable|string|max:255',
            'duration_minutes' => 'sometimes|integer|min:1',
            'order' => 'sometimes|integer|min:0',
            'status' => 'sometimes|in:pending,discussed,completed,skipped',
        ]);

        $agenda->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Agenda rapat berhasil diperbarui.',
                'data' => new \Modules\Agenda\Http\Resources\AgendaResource($agenda->fresh()),
            ], 200);
        }

        return redirect()->route('agendas.index', $meeting->id)->with('success', 'Agenda rapat berhasil diperbarui.');
    }

    public function apiDestroy(Request $request, string $meeting, string $agenda)
    {
        $meeting = MeetingModel::findOrFail($meeting);
        $agenda = AgendaModel::findOrFail($agenda);
        $agenda->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Agenda rapat berhasil dihapus.',
            ], 200);
        }

        return redirect()->route('agendas.index', $meeting->id)->with('success', 'Agenda rapat berhasil dihapus.');
    }

    // === VIEW METHODS ===

    public function index(Request $request, string $meeting)
    {
        if ($request->expectsJson()) {
            return $this->apiIndex($request, $meeting);
        }

        $meeting = MeetingModel::findOrFail($meeting);
        $agendas = $meeting->agendas()->ordered()->paginate(15)->withQueryString();

        return $this->renderPage('index', compact('meeting', 'agendas'));
    }

    public function create(string $meeting)
    {
        $meeting = MeetingModel::findOrFail($meeting);

        return $this->renderPage('create', compact('meeting'));
    }

    public function show(Request $request, string $meeting, string $agenda)
    {
        if ($request->expectsJson()) {
            return $this->apiShow($meeting, $agenda);
        }

        $meeting = MeetingModel::findOrFail($meeting);
        $agenda = AgendaModel::findOrFail($agenda);

        return $this->renderPage('show', compact('meeting', 'agenda'));
    }

    public function edit(string $meeting, string $agenda)
    {
        $meeting = MeetingModel::findOrFail($meeting);
        $agenda = AgendaModel::findOrFail($agenda);

        return $this->renderPage('edit', compact('meeting', 'agenda'));
    }
}
