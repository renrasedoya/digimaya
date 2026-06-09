<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_followup_notes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('followup_id')
                ->constrained('client_followups')
                ->cascadeOnDelete();

            $table->text('note');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('followup_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_followup_notes');
    }
};
