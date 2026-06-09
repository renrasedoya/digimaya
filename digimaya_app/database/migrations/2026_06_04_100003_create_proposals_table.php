<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('public_token', 64)->unique();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->json('content_blocks')->nullable();       // editable recipe
            $table->json('published_content')->nullable();     // frozen snapshot (set on publish)
            $table->timestamp('published_at')->nullable();     // updates on every (re)publish
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
