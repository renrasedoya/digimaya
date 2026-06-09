<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('public_id', 3)->unique();
            $table->string('question');
            $table->longText('answer');
            $table->boolean('is_active')->default(true);
            $table->integer('position_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'position_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
