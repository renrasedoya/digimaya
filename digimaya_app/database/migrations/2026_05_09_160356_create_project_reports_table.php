<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('submitted_by');
            $table->date('period_start');
            $table->date('period_end');
            $table->text('summary');
            $table->enum('health', ['healthy', 'needs_attention', 'critical'])->default('healthy');
            $table->unsignedBigInteger('issue_category_id')->nullable();
            $table->unsignedBigInteger('issue_sub_category_id')->nullable();
            $table->enum('status', ['open', 'in_progress', 'resolved'])->default('open');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('am_feedback')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->cascadeOnDelete();

            $table->foreign('submitted_by')
                  ->references('id')
                  ->on('users')
                  ->restrictOnDelete();

            $table->foreign('issue_category_id')
                  ->references('id')
                  ->on('issue_categories')
                  ->nullOnDelete();

            $table->foreign('issue_sub_category_id')
                  ->references('id')
                  ->on('issue_sub_categories')
                  ->nullOnDelete();

            $table->foreign('reviewed_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();

            $table->index(['project_id', 'period_start'], 'idx_pr_project_period');
            $table->index(['project_id', 'status'], 'idx_pr_project_status');
            $table->index(['submitted_by', 'created_at'], 'idx_pr_submitter');
            $table->index(['reviewed_by', 'reviewed_at'], 'idx_pr_reviewer');
            $table->index('health', 'idx_pr_health');
            $table->index('status', 'idx_pr_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_reports');
    }
};
