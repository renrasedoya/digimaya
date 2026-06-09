<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issue_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('issue_category_id');
            $table->string('name', 100);
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('issue_category_id')
                  ->references('id')
                  ->on('issue_categories')
                  ->cascadeOnDelete();

            $table->index(['issue_category_id', 'is_active', 'display_order'], 'idx_issue_sub_cat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_sub_categories');
    }
};
