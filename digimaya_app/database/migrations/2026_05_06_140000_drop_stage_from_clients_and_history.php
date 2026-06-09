<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('stage');
        });

        Schema::table('client_status_history', function (Blueprint $table) {
            $table->dropColumn(['stage_from', 'stage_to']);
        });
    }

    public function down(): void
    {
        // Re-add stage column to clients (with same enum from original migration)
        Schema::table('clients', function (Blueprint $table) {
            $table->enum('stage', [
                'new',
                'contacted',
                'interested',
                'negotiating',
                'closed_won',
                'closed_lost',
                'unresponsive',
            ])->default('new')->after('status');
        });

        Schema::table('client_status_history', function (Blueprint $table) {
            $table->string('stage_from', 50)->nullable()->after('status_to');
            $table->string('stage_to', 50)->nullable()->after('stage_from');
        });
    }
};
