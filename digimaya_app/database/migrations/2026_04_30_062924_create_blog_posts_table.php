<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('public_id', 3)->unique();
            $table->string('title', 200);
            $table->string('slug', 220);
            $table->longText('content')->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->string('youtube_video_id', 20)->nullable();
            $table->enum('status', ['draft', 'scheduled', 'published'])->default('draft');
            $table->dateTime('published_at')->nullable();
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('public_id');
            $table->index('status');
            $table->index('published_at');
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
