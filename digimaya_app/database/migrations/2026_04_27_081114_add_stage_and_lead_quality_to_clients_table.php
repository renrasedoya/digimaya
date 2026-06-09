<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

            $table->enum('lead_quality', [
                'poor',
                'average',
                'good',
            ])->nullable()->after('stage');

            $table->index('stage');
            $table->index('lead_quality');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex(['stage']);
            $table->dropIndex(['lead_quality']);
            $table->dropColumn(['stage', 'lead_quality']);
        });
    }
};
