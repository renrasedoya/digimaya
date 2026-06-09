<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('project_id')
                ->nullable()
                ->after('client_id')
                ->constrained('projects')
                ->nullOnDelete();

            $table->string('custom_client_name')->nullable()->after('project_id');
            $table->text('custom_client_address')->nullable()->after('custom_client_name');
            $table->string('custom_client_contact')->nullable()->after('custom_client_address');

            $table->date('period_start')->nullable()->after('custom_client_contact');
            $table->date('period_end')->nullable()->after('period_start');

            $table->index(['project_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['project_id', 'status']);
            $table->dropForeign(['project_id']);
            $table->dropColumn([
                'project_id',
                'custom_client_name',
                'custom_client_address',
                'custom_client_contact',
                'period_start',
                'period_end',
            ]);
        });
    }
};
