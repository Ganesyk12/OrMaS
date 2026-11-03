<?php

namespace Modules\Letter\Http\Controllers;

use App\Http\Controllers\Concerns\RendersModuleViews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Letter\Http\Resources\LetterResource;
use Modules\Letter\Models\LetterModel;
use Modules\LetterCategory\Models\LetterCategoryModel;

class LetterController extends Controller
{
    use RendersModuleViews;

    protected string $viewModule = 'letters';

    /**
     * Pastikan arah surat hanya incoming/outgoing.
     */
    private function resolveDirection(string $direction): string
    {
        abort_unless(in_array($direction, LetterModel::DIRECTIONS, true), 404);

        return $direction;
    }

    private function directionLabel(string $direction): string
    {
        return $direction === 'incoming' ? 'Surat masuk' : 'Surat keluar';
    }

    public function apiIndex(Request $request, string $direction)
    {
        $direction = $this->resolveDirection($direction);

        $query = LetterModel::query()
            ->with('category')
            ->direction($direction);

        if ($request->filled('search')) {
            $query->search($request->string('search'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $query->orderByDesc('letter_date')->orderByDesc('id');

        return LetterResource::collection($query->paginate($request->get('per_page', 15)))
            ->response()
            ->setStatusCode(200);
    }

    public function apiStore(Request $request, string $direction)
    {
        $direction = $this->resolveDirection($direction);

        $data = $request->validate([
            'category_id' => 'required|exists:letter_categories,id',
            'letter_number' => 'required|string|max:100',
            'subject' => 'required|string|max:255',
            'sender' => $direction === 'incoming' ? 'required|string|max:255' : 'nullable|string|max:255',
            'recipient' => $direction === 'outgoing' ? 'required|string|max:255' : 'nullable|string|max:255',
            'letter_date' => 'required|date',
            'received_date' => 'nullable|date|after_or_equal:letter_date',
            'notes' => 'nullable|string',
        ]);

        $letter = LetterModel::create(array_merge($data, [
            'direction' => $direction,
            'created_by' => $request->user()?->id,
        ]));

        $label = $this->directionLabel($direction);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "{$label} berhasil dibuat.",
                'data' => new LetterResource($letter->load('category')),
            ], 201);
        }

        return redirect()
            ->route('letters.index', ['direction' => $direction])
            ->with('success', "{$label} berhasil dibuat.");
    }

    public function apiShow(string $direction, string $letter)
    {
        $direction = $this->resolveDirection($direction);

        $letter = LetterModel::with('category')
            ->direction($direction)
            ->findOrFail($letter);

        return response()->json([
            'message' => 'Data surat.',
            'data' => new LetterResource($letter),
        ], 200);
    }

    public function apiUpdate(Request $request, string $direction, string $letter)
    {
        $direction = $this->resolveDirection($direction);

        $letter = LetterModel::direction($direction)->findOrFail($letter);

        $data = $request->validate([
            'category_id' => 'sometimes|exists:letter_categories,id',
            'letter_number' => 'sometimes|string|max:100',
            'subject' => 'sometimes|string|max:255',
            'sender' => 'nullable|string|max:255',
            'recipient' => 'nullable|string|max:255',
            'letter_date' => 'sometimes|date',
            'received_date' => 'nullable|date|after_or_equal:letter_date',
            'notes' => 'nullable|string',
        ]);

        $letter->update(array_merge($data, [
            'updated_by' => $request->user()?->id,
        ]));

        $label = $this->directionLabel($direction);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "{$label} berhasil diperbarui.",
                'data' => new LetterResource($letter->fresh()->load('category')),
            ], 200);
        }

        return redirect()
            ->route('letters.index', ['direction' => $direction])
            ->with('success', "{$label} berhasil diperbarui.");
    }

    public function apiDestroy(Request $request, string $direction, string $letter)
    {
        $direction = $this->resolveDirection($direction);

        $letter = LetterModel::direction($direction)->findOrFail($letter);
        $letter->delete();

        $label = $this->directionLabel($direction);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "{$label} berhasil dihapus.",
            ], 200);
        }

        return redirect()
            ->route('letters.index', ['direction' => $direction])
            ->with('success', "{$label} berhasil dihapus.");
    }

    // === VIEW METHODS ===

    public function index(Request $request, string $direction)
    {
        if ($request->expectsJson()) {
            return $this->apiIndex($request, $direction);
        }

        $direction = $this->resolveDirection($direction);

        $query = LetterModel::query()
            ->with('category')
            ->direction($direction);

        if ($request->filled('search')) {
            $query->search($request->string('search'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $letters = $query->orderByDesc('letter_date')->orderByDesc('id')->paginate(15)->withQueryString();
        $categories = LetterCategoryModel::orderBy('name')->get();

        return $this->renderPage('index', compact('letters', 'categories', 'direction'));
    }

    public function create(string $direction)
    {
        $direction = $this->resolveDirection($direction);
        $categories = LetterCategoryModel::active()->orderBy('name')->get();

        return $this->renderPage('create', compact('categories', 'direction'));
    }

    public function show(Request $request, string $direction, string $letter)
    {
        if ($request->expectsJson()) {
            return $this->apiShow($direction, $letter);
        }

        $direction = $this->resolveDirection($direction);

        $letter = LetterModel::with('category')
            ->direction($direction)
            ->findOrFail($letter);

        return $this->renderPage('show', compact('letter', 'direction'));
    }

    public function edit(string $direction, string $letter)
    {
        $direction = $this->resolveDirection($direction);

        $letter = LetterModel::direction($direction)->findOrFail($letter);

        $categories = LetterCategoryModel::query()
            ->where(function ($query) use ($letter) {
                $query->where('is_active', true)
                    ->orWhere('id', $letter->category_id);
            })
            ->orderBy('name')
            ->get();

        return $this->renderPage('edit', compact('letter', 'categories', 'direction'));
    }
}
