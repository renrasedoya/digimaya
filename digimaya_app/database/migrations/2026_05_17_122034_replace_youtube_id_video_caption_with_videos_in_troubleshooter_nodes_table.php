<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('troubleshooter_nodes', function (Blueprint $table) {
            $table->dropColumn(['youtube_id', 'video_caption']);
        });

        Schema::table('troubleshooter_nodes', function (Blueprint $table) {
            $table->json('videos')->nullable()->after('answers');
        });
    }

    public function down(): void
    {
        Schema::table('troubleshooter_nodes', function (Blueprint $table) {
            $table->dropColumn('videos');
        });

        Schema::table('troubleshooter_nodes', function (Blueprint $table) {
            $table->string('youtube_id', 20)->nullable()->after('answers');
            $table->text('video_caption')->nullable()->after('youtube_id');
        });
    }
};
