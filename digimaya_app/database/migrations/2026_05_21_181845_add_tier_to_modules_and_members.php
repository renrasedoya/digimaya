<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->enum('tier', ['free', 'paid'])->default('free')->after('is_published');
            $table->index('tier', 'idx_modules_tier');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->enum('tier', ['free', 'paid'])->default('free')->after('is_active');
            $table->index('tier', 'idx_members_tier');
        });
    }

    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropIndex('idx_modules_tier');
            $table->dropColumn('tier');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->dropIndex('idx_members_tier');
            $table->dropColumn('tier');
        });
    }
};
