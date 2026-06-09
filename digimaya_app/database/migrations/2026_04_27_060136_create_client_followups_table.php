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
        Schema::create('client_followups', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('client_id')
                ->constrained('clients')
                ->cascadeOnDelete();

            // Schedule
            $table->dateTime('scheduled_at');
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('next_followup_at')->nullable();

            // Details
            $table->enum('method', ['call', 'whatsapp', 'email', 'meeting', 'other']);
            $table->text('notes')->nullable();
            $table->enum('outcome', [
                'positive',
                'negative',
                'no_response',
                'closed_won',
                'closed_lost',
            ])->nullable();

            // Status
            $table->enum('status', ['pending', 'done', 'skipped'])
                ->default('pending');

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
            $table->index('status');
            $table->index(['status', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_followups');
    }
};
