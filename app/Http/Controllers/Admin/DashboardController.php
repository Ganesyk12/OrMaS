<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Activity\Models\ActivityModel;
use Modules\Finance\Models\FinanceDetailModel;
use Modules\Finance\Models\FinanceModel;
use Modules\Letter\Models\LetterModel;
use Modules\Member\Models\OrganizationMemberModel;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $organizationIds = $user?->isSuperAdmin() ? [] : ($user?->assignedOrganizationIds() ?? []);

        $members = OrganizationMemberModel::query()
            ->when($organizationIds !== [], fn ($query) => $query->whereIn('organization_id', $organizationIds))
            ->count();

        $letters = LetterModel::query()
            ->visibleToUser($user)
            ->count();

        $activitiesCount = ActivityModel::query()
            ->visibleToUser($user)
            ->count();

        $stats = [
            'members' => $members,
            'letters' => $letters,
            'activities' => $activitiesCount,
            'income' => FinanceDetailModel::whereHas('finance', fn ($query) => $query
                ->visibleToUser($user)
                ->where('type', 'income')
                ->where('status', 'approved'))->sum('amount') ?? 0,
            'expense' => FinanceDetailModel::whereHas('finance', fn ($query) => $query
                ->visibleToUser($user)
                ->where('type', 'expense')
                ->where('status', 'approved'))->sum('amount') ?? 0,
        ];

        $activities = ActivityModel::query()
            ->with('organization')
            ->visibleToUser($user)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $finances = FinanceModel::query()
            ->with('organization')
            ->withSum('details', 'amount')
            ->withCount('details')
            ->withMax('details', 'transaction_date')
            ->visibleToUser($user)
            ->orderByDesc('details_max_transaction_date')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $title = 'Dashboard';

        return view('dashboard.index', compact('stats', 'activities', 'finances', 'title'));
    }
}
