<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: drop existing JSON columns (data null karena belum diisi)
        Schema::table('troubleshooter_nodes', function (Blueprint $table) {
            $table->dropColumn(['causes', 'solutions']);
        });

        // Step 2: re-add sebagai LONGTEXT (sesuai pattern Quill di blog_posts)
        Schema::table('troubleshooter_nodes', function (Blueprint $table) {
            $table->longText('causes')->nullable()->after('label');
            $table->longText('solutions')->nullable()->after('causes');
        });
    }

    public function down(): void
    {
        Schema::table('troubleshooter_nodes', function (Blueprint $table) {
            $table->dropColumn(['causes', 'solutions']);
        });

        Schema::table('troubleshooter_nodes', function (Blueprint $table) {
            $table->json('causes')->nullable()->after('label');
            $table->json('solutions')->nullable()->after('causes');
        });
    }
};
