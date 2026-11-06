<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Activity\Models\ActivityModel;
use Modules\Finance\Models\FinanceDetailModel;
use Modules\Letter\Models\LetterModel;
use Modules\Member\Models\OrganizationMemberModel;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'members' => OrganizationMemberModel::count(),
            'letters' => LetterModel::count(),
            'activities' => ActivityModel::count(),
            'income' => FinanceDetailModel::whereHas('finance', fn ($query) => $query
                ->where('type', 'income')
                ->where('status', 'approved'))->sum('amount') ?? 0,
            'expense' => FinanceDetailModel::whereHas('finance', fn ($query) => $query
                ->where('type', 'expense')
                ->where('status', 'approved'))->sum('amount') ?? 0,
        ];

        $activities = ActivityModel::with('organization')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $title = 'Dashboard';

        return view('dashboard.index', compact('stats', 'activities', 'title'));
    }
}
