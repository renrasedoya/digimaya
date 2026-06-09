<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        // Recent activities — super_admin only
        $recentActivities = collect();
        if (Auth::user() && Auth::user()->isSuperAdmin()) {
            $recentActivities = Activity::with('causer')
                ->latest()
                ->take(8)
                ->get();
        }

        return view('admin.dashboard', compact('recentActivities'));
    }
}
