<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('troubleshooter_nodes', function (Blueprint $table) {
            $table->dropColumn(['causes', 'solutions']);
        });

        Schema::table('troubleshooter_nodes', function (Blueprint $table) {
            $table->json('answers')->nullable()->after('label');
        });
    }

    public function down(): void
    {
        Schema::table('troubleshooter_nodes', function (Blueprint $table) {
            $table->dropColumn('answers');
        });

        Schema::table('troubleshooter_nodes', function (Blueprint $table) {
            $table->longText('causes')->nullable()->after('label');
            $table->longText('solutions')->nullable()->after('causes');
        });
    }
};
