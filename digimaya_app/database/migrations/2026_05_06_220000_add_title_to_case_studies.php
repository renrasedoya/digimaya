<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Stage 1: add nullable column
        Schema::table('case_studies', function (Blueprint $table) {
            $table->string('title', 255)->nullable()->after('client_name');
        });

        // Stage 2: backfill existing rows with client_name as placeholder
        DB::table('case_studies')
            ->whereNull('title')
            ->update(['title' => DB::raw('client_name')]);

        // Stage 3: change to NOT NULL
        Schema::table('case_studies', function (Blueprint $table) {
            $table->string('title', 255)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
