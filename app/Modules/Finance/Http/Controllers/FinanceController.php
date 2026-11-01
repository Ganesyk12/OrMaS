<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Concerns\RendersModuleViews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Finance\Models\FinanceModel;

class FinanceController extends Controller
{
    use RendersModuleViews;

    protected string $viewModule = 'finances';

    public function apiIndex(Request $request)
    {
        $query = FinanceModel::query()->with(['organization', 'activity']);

        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->filled('type')) {
            $query->type($request->type);
        }
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        return \Modules\Finance\Http\Resources\FinanceResource::collection($query->paginate($request->get('per_page', 15)))
            ->response()
            ->setStatusCode(200);
    }

    public function apiStore(Request $request)
    {
        $data = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'activity_id' => 'nullable|exists:activities,id',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
            'receipt' => 'nullable|file|max:2048',
            'status' => 'sometimes|in:draft,approved,rejected',
        ]);

        if ($request->hasFile('receipt')) {
            $data['receipt'] = $request->file('receipt')->store('finance-receipts', 'public');
        }

        $finance = FinanceModel::create(array_merge($data, [
            'created_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Transaksi keuangan berhasil dibuat.',
                'data' => new \Modules\Finance\Http\Resources\FinanceResource($finance->load(['organization', 'activity'])),
            ], 201);
        }

        return redirect()->route('finances.index')->with('success', 'Transaksi keuangan berhasil dibuat.');
    }

    public function apiShow(string $finance)
    {
        $finance = FinanceModel::findOrFail($finance);

        return response()->json([
            'message' => 'Data keuangan.',
            'data' => new \Modules\Finance\Http\Resources\FinanceResource($finance->load(['organization', 'activity'])),
        ], 200);
    }

    public function apiUpdate(Request $request, string $finance)
    {
        $finance = FinanceModel::findOrFail($finance);

        $data = $request->validate([
            'organization_id' => 'sometimes|exists:organizations,id',
            'activity_id' => 'nullable|exists:activities,id',
            'type' => 'sometimes|in:income,expense',
            'category' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string',
            'transaction_date' => 'sometimes|date',
            'receipt' => 'nullable|file|max:2048',
            'status' => 'sometimes|in:draft,approved,rejected',
        ]);

        if ($request->hasFile('receipt')) {
            $data['receipt'] = $request->file('receipt')->store('finance-receipts', 'public');
        }

        $finance->update(array_merge($data, [
            'updated_by' => $request->user()?->id,
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Transaksi keuangan berhasil diperbarui.',
                'data' => new \Modules\Finance\Http\Resources\FinanceResource($finance->fresh()->load(['organization', 'activity'])),
            ], 200);
        }

        return redirect()->route('finances.index')->with('success', 'Transaksi keuangan berhasil diperbarui.');
    }

    public function apiDestroy(Request $request, string $finance)
    {
        $finance = FinanceModel::findOrFail($finance);
        $finance->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Transaksi keuangan berhasil dihapus.',
            ], 200);
        }

        return redirect()->route('finances.index')->with('success', 'Transaksi keuangan berhasil dihapus.');
    }

    // === VIEW METHODS ===

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            return $this->apiIndex($request);
        }

        $query = FinanceModel::query()->with(['organization', 'activity']);

        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->filled('type')) {
            $query->type($request->type);
        }
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        $finances = $query->paginate(15)->withQueryString();
        $organizations = \Modules\Organization\Models\OrganizationModel::active()->get();
        $activities = \Modules\Activity\Models\ActivityModel::all();

        return $this->renderPage('index', compact('finances', 'organizations', 'activities'));
    }

    public function create()
    {
        $organizations = \Modules\Organization\Models\OrganizationModel::active()->get();
        $activities = \Modules\Activity\Models\ActivityModel::all();

        return $this->renderPage('create', compact('organizations', 'activities'));
    }

    public function show(Request $request, string $finance)
    {
        if ($request->expectsJson()) {
            return $this->apiShow($finance);
        }

        $finance = FinanceModel::with(['organization', 'activity'])->findOrFail($finance);

        return $this->renderPage('show', compact('finance'));
    }

    public function edit(string $finance)
    {
        $finance = FinanceModel::findOrFail($finance);
        $organizations = \Modules\Organization\Models\OrganizationModel::active()->get();
        $activities = \Modules\Activity\Models\ActivityModel::all();

        return $this->renderPage('edit', compact('finance', 'organizations', 'activities'));
    }
}
