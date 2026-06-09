<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_followups', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['status', 'scheduled_at']);
            $table->dropColumn(['status', 'notes']);
        });
    }

    public function down(): void
    {
        Schema::table('client_followups', function (Blueprint $table) {
            $table->enum('status', ['pending', 'done', 'skipped'])
                ->default('pending')
                ->after('outcome');
            $table->text('notes')->nullable()->after('method');
            $table->index('status');
            $table->index(['status', 'scheduled_at']);
        });
    }
};
