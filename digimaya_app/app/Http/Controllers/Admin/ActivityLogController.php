<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Available log_name values mapped to readable labels.
     */
    public const LOG_NAMES = [
        'client' => 'Client',
        'followup' => 'Followup',
        'followup_note' => 'Followup Note',
        'invoice' => 'Invoice',
        'income' => 'Income',
        'expense' => 'Expense',
        'service' => 'Service',
        'blog_post' => 'Blog Post',
        'blog_category' => 'Blog Category',
    ];

    /**
     * Available events mapped to readable labels.
     */
    public const EVENTS = [
        'created' => 'Created',
        'updated' => 'Updated',
        'deleted' => 'Deleted',
        'restored' => 'Restored',
    ];

    /**
     * Available date range presets mapped to readable labels.
     */
    public const DATE_PRESETS = [
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'last_7_days' => 'Last 7 Days',
        'this_month' => 'This Month',
        'last_month' => 'Last Month',
    ];

    /**
     * Display the full activity log list with filters.
     */
    public function index(Request $request): View
    {
        $datePreset = $request->input('date_preset', 'today');
        $userId = $request->input('user_id');
        $logName = $request->input('log_name');
        $event = $request->input('event');

        // Default if invalid preset
        if (!array_key_exists($datePreset, self::DATE_PRESETS)) {
            $datePreset = 'today';
        }

        [$dateFrom, $dateTo] = $this->resolveDateRange($datePreset);

        $query = Activity::with('causer')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->latest();

        if ($userId) {
            $query->where('causer_id', $userId)->where('causer_type', User::class);
        }

        if ($logName && array_key_exists($logName, self::LOG_NAMES)) {
            $query->where('log_name', $logName);
        }

        if ($event && array_key_exists($event, self::EVENTS)) {
            $query->where('event', $event);
        }

        $activities = $query->paginate(30)->withQueryString();

        $users = User::orderBy('name')->get(['id', 'name']);
        $logNames = self::LOG_NAMES;
        $events = self::EVENTS;
        $datePresets = self::DATE_PRESETS;

        return view('admin.activity-log.index', compact(
            'activities',
            'users',
            'logNames',
            'events',
            'datePresets',
            'datePreset',
            'userId',
            'logName',
            'event',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Convert a date preset key to a [from, to] date range.
     */
    private function resolveDateRange(string $preset): array
    {
        return match ($preset) {
            'today' => [
                Carbon::today()->toDateString(),
                Carbon::today()->toDateString(),
            ],
            'yesterday' => [
                Carbon::yesterday()->toDateString(),
                Carbon::yesterday()->toDateString(),
            ],
            'last_7_days' => [
                Carbon::today()->subDays(6)->toDateString(),
                Carbon::today()->toDateString(),
            ],
            'this_month' => [
                Carbon::now()->startOfMonth()->toDateString(),
                Carbon::today()->toDateString(),
            ],
            'last_month' => [
                Carbon::now()->subMonthNoOverflow()->startOfMonth()->toDateString(),
                Carbon::now()->subMonthNoOverflow()->endOfMonth()->toDateString(),
            ],
            default => [
                Carbon::today()->toDateString(),
                Carbon::today()->toDateString(),
            ],
        };
    }
}
