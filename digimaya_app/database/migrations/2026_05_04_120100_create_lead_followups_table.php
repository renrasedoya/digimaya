<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lead_followups', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('lead_id')
                ->constrained('leads')
                ->cascadeOnDelete();

            // Schedule
            $table->dateTime('scheduled_at');
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('next_followup_at')->nullable();

            // Details
            $table->enum('method', ['call', 'whatsapp', 'email', 'meeting', 'other']);
            $table->text('notes')->nullable();
            $table->enum('outcome', [
                'positive',           // ada potensi
                'negative',           // gak fit
                'no_response',        // gak nyambut
                'screening_passed',   // pantas dipromote ke prospect
                'screening_failed',   // tidak pantas, akan disqualified
            ])->nullable();

            // Audit trail
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Timestamps + soft delete
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('scheduled_at');
            $table->index('lead_id');
            $table->index(['lead_id', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_followups');
    }
};
