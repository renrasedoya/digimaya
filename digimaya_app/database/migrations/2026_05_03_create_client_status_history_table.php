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
        Schema::create('client_status_history', function (Blueprint $table) {
            $table->id();
            // Relations
            $table->foreignId('client_id')
                ->constrained('clients')
                ->cascadeOnDelete();
            // Status transition
            $table->string('status_from', 32)->nullable();
            $table->string('status_to', 32)->nullable();
            // Stage transition
            $table->string('stage_from', 32)->nullable();
            $table->string('stage_to', 32)->nullable();
            // Event timestamp
            $table->dateTime('changed_at');
            // Optional context
            $table->text('notes')->nullable();
            // Audit trail
            $table->foreignId('changed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            // Timestamps
            $table->timestamps();
            // Indexes — heavy read on dashboard queries
            $table->index('changed_at');
            $table->index(['client_id', 'changed_at']);
            $table->index(['status_to', 'changed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_status_history');
    }
};
