<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_reports', function (Blueprint $table) {
            $table->timestamp('acknowledged_at')->nullable()->after('reviewed_at');
            $table->index('acknowledged_at');
        });
    }

    public function down(): void
    {
        Schema::table('project_reports', function (Blueprint $table) {
            $table->dropIndex(['acknowledged_at']);
            $table->dropColumn('acknowledged_at');
        });
    }
};
