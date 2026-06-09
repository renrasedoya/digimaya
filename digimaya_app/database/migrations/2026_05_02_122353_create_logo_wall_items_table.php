<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logo_wall_items', function (Blueprint $table) {
            $table->id();
            $table->string('public_id', 3)->unique();
            $table->string('name', 255);
            $table->string('image', 500)->nullable(); // file path OR external URL
            $table->string('group', 50); // slug-style: lowercase + underscore + numbers
            $table->boolean('is_active')->default(true);
            $table->integer('position_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('group');
            $table->index(['is_active', 'position_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logo_wall_items');
    }
};
