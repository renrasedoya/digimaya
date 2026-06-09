<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_snippets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->nullable();   // grouping label (usp, about, terms, etc.)
            $table->longText('body');                  // reusable HTML content, sanitized on save
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_snippets');
    }
};
