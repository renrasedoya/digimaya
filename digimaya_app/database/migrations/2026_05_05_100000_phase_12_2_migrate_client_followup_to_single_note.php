<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 12.2 — Migrate ClientFollowup ke single-note pattern (mirror Lead).
     *
     * 1. Add `notes` column ke client_followups (text, nullable)
     * 2. Drop client_followup_notes table entirely (data lama dihapus per user decision)
     */
    public function up(): void
    {
        Schema::table('client_followups', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('outcome');
        });

        Schema::dropIfExists('client_followup_notes');
    }

    /**
     * Rollback: re-create client_followup_notes table + drop notes column.
     * Note: data lama tidak bisa di-restore (sudah dihapus di up()).
     */
    public function down(): void
    {
        Schema::table('client_followups', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::create('client_followup_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('followup_id')->constrained('client_followups')->onDelete('cascade');
            $table->text('note');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('followup_id');
        });
    }
};
