<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OperationsClientController extends Controller
{
    /**
     * AM-only read-only list of clients assigned to the logged-in AM.
     * Excludes prospect status (AM cannot be assigned to prospect per business rule).
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if (!$user->isAccountManager()) {
            abort(403, 'Halaman ini hanya untuk Account Manager.');
        }

        $allowedStatuses = ['active', 'inactive', 'churned'];

        $query = Client::query()
            ->where('account_manager_id', $user->id)
            ->whereIn('status', $allowedStatuses);

            if ($request->input('filter') === 'no_project') {
                $query->whereDoesntHave('projects');
            }

        if ($request->filled('status') && in_array($request->status, $allowedStatuses, true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Status counts (scoped to this AM only)
        $countQuery = Client::query()
            ->where('account_manager_id', $user->id)
            ->whereIn('status', $allowedStatuses);

        $statusCounts = [
            'total'    => (clone $countQuery)->count(),
            'active'   => (clone $countQuery)->where('status', 'active')->count(),
            'inactive' => (clone $countQuery)->where('status', 'inactive')->count(),
            'churned'  => (clone $countQuery)->where('status', 'churned')->count(),
        ];

        return view('admin.operations.clients.index', compact('clients', 'statusCounts'));
    }
}
