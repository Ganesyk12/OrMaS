<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Concerns\RendersModuleViews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Activity\Models\ActivityModel;
use Modules\Finance\Http\Resources\FinanceResource;
use Modules\Finance\Models\FinanceModel;
use Modules\Organization\Models\OrganizationModel;

class FinanceController extends Controller
{
    use RendersModuleViews;

    protected string $viewModule = 'finances';

    public function apiIndex(Request $request)
    {
        return FinanceResource::collection($this->filteredQuery($request)->paginate($request->get('per_page', 15)))
            ->response()
            ->setStatusCode(200);
    }

    public function apiStore(Request $request)
    {
        $data = $this->validateFinance($request, true);
        $details = $data['details'];
        unset($data['details']);

        $email = $request->user()?->email;

        $finance = DB::transaction(function () use ($data, $details, $request, $email) {
            $finance = FinanceModel::create(array_merge($data, [
                'status' => $data['status'] ?? 'draft',
                'created_by' => $email,
            ]));

            $this->syncDetails($finance, $details, $request, $email);

            return $finance;
        });

        $finance->load(['organization', 'activity', 'details']);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Transaksi keuangan berhasil dibuat.',
                'data' => new FinanceResource($finance),
            ], 201);
        }

        return redirect()->route('finances.index')->with('success', 'Transaksi keuangan berhasil dibuat.');
    }

    public function apiShow(string $finance)
    {
        $finance = FinanceModel::with(['organization', 'activity', 'details'])->findOrFail($finance);

        return response()->json([
            'message' => 'Data keuangan.',
            'data' => new FinanceResource($finance),
        ], 200);
    }

    public function apiUpdate(Request $request, string $finance)
    {
        $finance = FinanceModel::findOrFail($finance);

        $data = $this->validateFinance($request, false);
        $details = $data['details'] ?? null;
        unset($data['details']);

        $email = $request->user()?->email;

        DB::transaction(function () use ($finance, $data, $details, $request, $email) {
            $finance->update(array_merge($data, [
                'updated_by' => $email,
            ]));

            if ($details !== null) {
                $this->syncDetails($finance, $details, $request, $email);
            }
        });

        $finance = $finance->fresh()->load(['organization', 'activity', 'details']);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Transaksi keuangan berhasil diperbarui.',
                'data' => new FinanceResource($finance),
            ], 200);
        }

        return redirect()->route('finances.index')->with('success', 'Transaksi keuangan berhasil diperbarui.');
    }

    public function apiDestroy(Request $request, string $finance)
    {
        $finance = FinanceModel::findOrFail($finance);

        DB::transaction(function () use ($finance) {
            $finance->details()->delete();
            $finance->delete();
        });

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

        $finances = $this->filteredQuery($request)->paginate(15)->withQueryString();
        $organizations = OrganizationModel::active()->get();
        $activities = ActivityModel::all();

        return $this->renderPage('index', compact('finances', 'organizations', 'activities'));
    }

    public function create()
    {
        $organizations = OrganizationModel::active()->get();
        $activities = ActivityModel::all();

        return $this->renderPage('create', compact('organizations', 'activities'));
    }

    public function show(Request $request, string $finance)
    {
        if ($request->expectsJson()) {
            return $this->apiShow($finance);
        }

        $finance = FinanceModel::with(['organization', 'activity', 'details'])->findOrFail($finance);

        return $this->renderPage('show', compact('finance'));
    }

    public function edit(string $finance)
    {
        $finance = FinanceModel::with('details')->findOrFail($finance);
        $organizations = OrganizationModel::active()->get();
        $activities = ActivityModel::all();

        return $this->renderPage('edit', compact('finance', 'organizations', 'activities'));
    }

    /**
     * Query daftar transaksi beserta akumulasi nominal dari detail.
     */
    private function filteredQuery(Request $request)
    {
        $query = FinanceModel::query()
            ->with(['organization', 'activity'])
            ->withSum('details', 'amount')
            ->withCount('details');

        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }
        if ($request->filled('type')) {
            $query->type($request->type);
        }
        if ($request->filled('status')) {
            $query->status($request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('details', function ($detailQuery) use ($search) {
                $detailQuery->where('category', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Validasi header transaksi beserta detailnya.
     */
    private function validateFinance(Request $request, bool $creating): array
    {
        $required = $creating ? 'required' : 'sometimes';

        return $request->validate([
            'organization_id' => "{$required}|exists:organizations,id",
            'activity_id' => 'nullable|exists:activities,id',
            'type' => "{$required}|in:income,expense",
            'transaction_date' => "{$required}|date",
            'status' => 'sometimes|in:draft,approved,rejected',
            'details' => "{$required}|array|min:1",
            'details.*.id' => 'nullable|integer',
            'details.*.category' => 'required|string|max:255',
            'details.*.amount' => 'required|numeric|min:0',
            'details.*.receipt' => 'nullable|file|max:2048',
        ]);
    }

    /**
     * Sinkronisasi detail transaksi (tambah, ubah, hapus).
     */
    private function syncDetails(FinanceModel $finance, array $details, Request $request, ?string $email): void
    {
        $keptIds = [];

        foreach ($details as $index => $detail) {
            $payload = [
                'category' => $detail['category'],
                'amount' => $detail['amount'],
                'updated_by' => $email,
            ];

            if ($request->hasFile("details.{$index}.receipt")) {
                $payload['receipt'] = $request->file("details.{$index}.receipt")
                    ->store('finance-receipts', 'public');
            }

            $existing = ! empty($detail['id'])
                ? $finance->details()->find($detail['id'])
                : null;

            if ($existing) {
                $existing->update($payload);
                $keptIds[] = $existing->id;

                continue;
            }

            $payload['created_by'] = $email;
            $keptIds[] = $finance->details()->create($payload)->id;
        }

        $finance->details()->whereNotIn('id', $keptIds)->delete();
    }
}
