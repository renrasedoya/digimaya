<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('advertiser_id');
            $table->string('name', 255);
            $table->string('account_url', 500)->nullable();
            $table->enum('status', ['active', 'paused', 'completed'])->default('active');
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')
                  ->references('id')
                  ->on('clients')
                  ->cascadeOnDelete();

            $table->foreign('advertiser_id')
                  ->references('id')
                  ->on('users')
                  ->restrictOnDelete();

            $table->index(['client_id', 'status'], 'idx_projects_client_status');
            $table->index(['advertiser_id', 'status'], 'idx_projects_advertiser_status');
            $table->index('status', 'idx_projects_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
