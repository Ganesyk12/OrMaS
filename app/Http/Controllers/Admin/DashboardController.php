<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Organization\Models\OrganizationModel;
use Modules\Member\Models\OrganizationMemberModel;
use Modules\Activity\Models\ActivityModel;
use Modules\Finance\Models\FinanceModel;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'organizations' => OrganizationModel::count(),
            'members' => OrganizationMemberModel::count(),
            'activities' => ActivityModel::count(),
            'income' => FinanceModel::where('type', 'income')->where('status', 'approved')->sum('amount') ?? 0,
            'expense' => FinanceModel::where('type', 'expense')->where('status', 'approved')->sum('amount') ?? 0,
        ];

        $activities = ActivityModel::with('organization')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'activities'));
    }
}
