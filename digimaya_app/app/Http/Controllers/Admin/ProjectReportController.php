<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IssueSubCategory;
use App\Models\Project;
use App\Models\ProjectReport;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProjectReportController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $user = $request->user();
        $this->ensureCanSubmit($user, $project);

        $validated = $this->validateReport($request);
        $validated['project_id'] = $project->id;
        $validated['submitted_by'] = $user->id;
        $validated['status'] = ProjectReport::STATUS_OPEN;

        ProjectReport::create($validated);

        return redirect()
            ->route('admin.projects.show', $project)
            ->with('success', 'Report submitted successfully.');
    }

    public function update(Request $request, ProjectReport $report): RedirectResponse
    {
        $user = $request->user();

        if (!$report->canBeEditedBy($user)) {
            throw new AuthorizationException('You cannot edit this report.');
        }

        $validated = $this->validateReport($request);

        // Advertiser cannot change status / reviewer fields via edit
        // Only update content fields
        $report->update([
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'summary' => $validated['summary'],
            'health' => $validated['health'],
            'issue_category_id' => $validated['issue_category_id'] ?? null,
            'issue_sub_category_id' => $validated['issue_sub_category_id'] ?? null,
        ]);

        return redirect()
            ->route('admin.projects.show', $report->project)
            ->with('success', 'Report updated successfully.');
    }

    public function destroy(Request $request, ProjectReport $report): RedirectResponse
    {
        $user = $request->user();

        // Super admin / admin can always delete
        // AM (project's AM) can delete
        // Advertiser can delete own report only if not yet resolved
        if (!$this->canDelete($user, $report)) {
            throw new AuthorizationException('You cannot delete this report.');
        }

        $project = $report->project;
        $report->delete();

        return redirect()
            ->route('admin.projects.show', $project)
            ->with('success', 'Report deleted successfully.');
    }

    /**
     * AM review action: update status + feedback. Sets reviewed_by/reviewed_at.
     */
    public function review(Request $request, ProjectReport $report): RedirectResponse
    {
        $user = $request->user();
        $this->ensureCanReview($user, $report);

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(ProjectReport::STATUSES))],
            'am_feedback' => ['nullable', 'string'],
        ]);

        $report->update([
            'status' => $validated['status'],
            'am_feedback' => $validated['am_feedback'] ?? null,
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('admin.projects.show', $report->project)
            ->with('success', 'Report reviewed successfully.');
    }

    public function acknowledge(Request $request, ProjectReport $report): RedirectResponse
    {
        $user = $request->user();

        // Only the advertiser who submitted this report can acknowledge
        if ($user->id !== (int) $report->submitted_by) {
            throw new AuthorizationException('You can only acknowledge your own reports.');
        }

        // Must be reviewed by AM first
        if (!$report->isReviewed()) {
            return back()->with('error', 'This report has not been reviewed yet.');
        }

        // Idempotent — silent skip if already acknowledged
        if ($report->isAcknowledged()) {
            return back()->with('info', 'Report already acknowledged.');
        }

        $report->update(['acknowledged_at' => now()]);

        return back()->with('success', 'Report acknowledged successfully.');
    }

    // ===== ACCESS HELPERS =====

    private function ensureCanViewProject(User $user, Project $project): void
    {
        if (in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN], true)) {
            return;
        }

        if ($user->isAccountManager()) {
            if (($project->client->account_manager_id ?? null) !== $user->id) {
                throw new AuthorizationException('You can only view reports of projects you manage.');
            }
            return;
        }

        if ($user->isAdvertiser()) {
            if ($project->advertiser_id !== $user->id) {
                throw new AuthorizationException('You can only view reports of projects assigned to you.');
            }
            return;
        }

        throw new AuthorizationException('You do not have permission to view this report.');
    }

    /**
     * Only the assigned advertiser can submit. Project must be active.
     */
    private function ensureCanSubmit(User $user, Project $project): void
    {
        if (!$user->isAdvertiser()) {
            throw new AuthorizationException('Only advertisers can submit reports.');
        }

        if ($project->advertiser_id !== $user->id) {
            throw new AuthorizationException('You can only submit reports for projects assigned to you.');
        }

        if (!$project->isActive()) {
            throw new AuthorizationException('Reports can only be submitted for active projects. Current status: ' . $project->status_label . '.');
        }
    }

    /**
     * Only AM (project's AM) + super_admin/admin can review.
     */
    private function ensureCanReview(User $user, ProjectReport $report): void
    {
        if (in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN], true)) {
            return;
        }

        if ($user->isAccountManager()) {
            $projectAmId = $report->project->client->account_manager_id ?? null;
            if ($projectAmId !== $user->id) {
                throw new AuthorizationException('You can only review reports of projects you manage.');
            }
            return;
        }

        throw new AuthorizationException('You do not have permission to review reports.');
    }

    private function canDelete(User $user, ProjectReport $report): bool
    {
        if (in_array($user->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN], true)) {
            return true;
        }

        if ($user->isAccountManager()) {
            return ($report->project->client->account_manager_id ?? null) === $user->id;
        }

        if ($user->isAdvertiser()) {
            return $report->submitted_by === $user->id && !$report->isResolved();
        }

        return false;
    }

    /**
     * Shared validation. Issue is required only if health != healthy.
     */
    private function validateReport(Request $request): array
    {
        $rules = [
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
            'summary' => ['required', 'string'],
            'health' => ['required', Rule::in(array_keys(ProjectReport::HEALTHS))],
            'issue_category_id' => ['nullable', 'integer', 'exists:issue_categories,id'],
            'issue_sub_category_id' => ['nullable', 'integer', 'exists:issue_sub_categories,id'],
        ];

        // Conditional: issue REQUIRED if health != healthy
        if ($request->input('health') !== ProjectReport::HEALTH_HEALTHY) {
            $rules['issue_category_id'] = ['required', 'integer', 'exists:issue_categories,id'];
            $rules['issue_sub_category_id'] = ['required', 'integer', 'exists:issue_sub_categories,id'];
        }

        $validated = $request->validate($rules);

        // Verify sub-category belongs to category
        if (!empty($validated['issue_category_id']) && !empty($validated['issue_sub_category_id'])) {
            $belongs = IssueSubCategory::where('id', $validated['issue_sub_category_id'])
                ->where('issue_category_id', $validated['issue_category_id'])
                ->exists();

            if (!$belongs) {
                throw ValidationException::withMessages([
                    'issue_sub_category_id' => 'Sub-category tidak match dengan category yang dipilih.',
                ]);
            }
        }

        return $validated;
    }
}
