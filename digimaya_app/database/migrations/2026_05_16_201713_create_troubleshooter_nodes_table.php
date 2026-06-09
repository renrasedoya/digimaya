<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('troubleshooter_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('troubleshooter_nodes')
                  ->cascadeOnDelete();
            $table->enum('type', ['question', 'leaf'])->default('question');
            $table->string('label');
            $table->json('causes')->nullable();
            $table->json('solutions')->nullable();
            $table->string('youtube_id', 20)->nullable();
            $table->text('video_caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['parent_id', 'sort_order', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('troubleshooter_nodes');
    }
};
