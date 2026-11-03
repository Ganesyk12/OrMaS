<?php

namespace Modules\LetterCategory\Http\Controllers;

use App\Http\Controllers\Concerns\RendersModuleViews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\LetterCategory\Http\Resources\LetterCategoryResource;
use Modules\LetterCategory\Models\LetterCategoryModel;

class LetterCategoryController extends Controller
{
    use RendersModuleViews;

    protected string $viewModule = 'letter-categories';

    public function apiIndex(Request $request)
    {
        $query = LetterCategoryModel::query()->withCount('letters');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($query) use ($search) {
                $query->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        return LetterCategoryResource::collection($query->orderBy('name')->paginate($request->get('per_page', 15)))
            ->response()
            ->setStatusCode(200);
    }

    public function apiStore(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:letter_categories,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $category = LetterCategoryModel::create(array_merge($data, [
            'created_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Kategori surat berhasil dibuat.',
                'data' => new LetterCategoryResource($category->loadCount('letters')),
            ], 201);
        }

        return redirect()->route('letter-categories.index')->with('success', 'Kategori surat berhasil dibuat.');
    }

    public function apiShow(string $letterCategory)
    {
        $category = LetterCategoryModel::withCount('letters')->findOrFail($letterCategory);

        return response()->json([
            'message' => 'Data kategori surat.',
            'data' => new LetterCategoryResource($category),
        ], 200);
    }

    public function apiUpdate(Request $request, string $letterCategory)
    {
        $category = LetterCategoryModel::findOrFail($letterCategory);

        $data = $request->validate([
            'code' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('letter_categories', 'code')->ignore($category->id),
            ],
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $category->update(array_merge($data, [
            'updated_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Kategori surat berhasil diperbarui.',
                'data' => new LetterCategoryResource($category->fresh()->loadCount('letters')),
            ], 200);
        }

        return redirect()->route('letter-categories.index')->with('success', 'Kategori surat berhasil diperbarui.');
    }

    public function apiDestroy(Request $request, string $letterCategory)
    {
        $category = LetterCategoryModel::withCount('letters')->findOrFail($letterCategory);

        if ($category->letters_count > 0) {
            $message = 'Kategori tidak dapat dihapus karena masih digunakan oleh surat.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()->route('letter-categories.index')->with('error', $message);
        }

        $category->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Kategori surat berhasil dihapus.',
            ], 200);
        }

        return redirect()->route('letter-categories.index')->with('success', 'Kategori surat berhasil dihapus.');
    }

    // === VIEW METHODS ===

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            return $this->apiIndex($request);
        }

        $query = LetterCategoryModel::query()->withCount('letters');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($query) use ($search) {
                $query->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $categories = $query->orderBy('name')->paginate(15)->withQueryString();

        return $this->renderPage('index', compact('categories'));
    }

    public function create()
    {
        return $this->renderPage('create');
    }

    public function show(Request $request, string $letterCategory)
    {
        if ($request->expectsJson()) {
            return $this->apiShow($letterCategory);
        }

        $category = LetterCategoryModel::withCount('letters')
            ->with(['letters' => fn ($query) => $query->orderByDesc('letter_date')->limit(10)])
            ->findOrFail($letterCategory);

        return $this->renderPage('show', compact('category'));
    }

    public function edit(string $letterCategory)
    {
        $category = LetterCategoryModel::findOrFail($letterCategory);

        return $this->renderPage('edit', compact('category'));
    }
}
