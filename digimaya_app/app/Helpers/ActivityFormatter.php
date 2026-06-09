<?php

namespace App\Helpers;

use Spatie\Activitylog\Models\Activity;

/**
 * Format Activity records for human-readable display.
 *
 * Used by dashboard card + full activity log page.
 */
class ActivityFormatter
{
    /**
     * Action verb based on event type.
     * E.g., "created", "updated", "deleted".
     */
    public static function actionVerb(Activity $activity): string
    {
        // Special case: ClientFollowupNote `created` → "added a note to"
        if ($activity->log_name === 'followup_note' && $activity->event === 'created') {
            return 'added a note to';
        }

        return match ($activity->event) {
            'created' => 'created',
            'updated' => 'updated',
            'deleted' => 'deleted',
            'restored' => 'restored',
            default => $activity->event ?? 'modified',
        };
    }

    /**
     * Subject label — human-readable identifier per model type.
     * E.g., "Client #5 (kaaba)", "Invoice DGMY/2026/04/001", "Blog Post 'Test'".
     */
    public static function subjectLabel(Activity $activity): string
    {
        $type = class_basename($activity->subject_type ?? '');
        $id = $activity->subject_id;
        $subject = $activity->subject; // Lazy-loaded — null if hard deleted

        return match ($type) {
            'Client' => $subject
                ? "Client #{$id} ({$subject->name})"
                : "Client #{$id}",

            'ClientFollowup' => "Followup #{$id}",

            'ClientFollowupNote' => self::followupNoteLabel($activity),

            'Invoice' => $subject
                ? "Invoice {$subject->invoice_number}"
                : "Invoice #{$id}",

            'Income' => $subject
                ? "Income #{$id} (" . substr($subject->description ?? '', 0, 40) . ")"
                : "Income #{$id}",

            'Expense' => $subject
                ? "Expense #{$id} (" . substr($subject->description ?? '', 0, 40) . ")"
                : "Expense #{$id}",

            'Service' => $subject
                ? "Service ({$subject->name})"
                : "Service #{$id}",

            'BlogPost' => $subject
                ? "Blog Post '" . \Illuminate\Support\Str::limit($subject->title, 40) . "'"
                : "Blog Post #{$id}",

            'BlogCategory' => $subject
                ? "Blog Category ({$subject->name})"
                : "Blog Category #{$id}",

            default => "{$type} #{$id}",
        };
    }

    /**
     * Special label for ClientFollowupNote — point to parent Followup.
     */
    private static function followupNoteLabel(Activity $activity): string
    {
        $note = $activity->subject;
        if ($note && $note->followup_id) {
            return "Followup #{$note->followup_id}";
        }

        // Fallback: use properties if note was deleted
        $props = $activity->properties;
        if ($props && isset($props['attributes']['followup_id'])) {
            return "Followup #{$props['attributes']['followup_id']}";
        }

        return "Note #{$activity->subject_id}";
    }

    /**
     * Compact diff summary for properties (for full page detail view).
     * Returns array of "field: old → new" strings.
     */
    public static function diffSummary(Activity $activity): array
    {
        $props = $activity->properties;
        if (!$props || !isset($props['attributes'])) {
            return [];
        }

        $new = $props['attributes'] ?? [];
        $old = $props['old'] ?? [];

        $rows = [];
        foreach ($new as $field => $newValue) {
            $oldValue = $old[$field] ?? null;
            $rows[] = [
                'field' => self::humanizeField($field),
                'old' => self::formatValue($oldValue),
                'new' => self::formatValue($newValue),
            ];
        }

        return $rows;
    }

    /**
     * Convert snake_case field name to Title Case.
     */
    public static function humanizeField(string $field): string
    {
        return ucwords(str_replace(['_id', '_'], ['', ' '], $field));
    }

    /**
     * Format value for display — handles null, dates, booleans.
     */
    public static function formatValue($value): string
    {
        if (is_null($value)) {
            return '—';
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        return (string) $value;
    }
}
