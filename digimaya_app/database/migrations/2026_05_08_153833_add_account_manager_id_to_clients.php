<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedBigInteger('account_manager_id')
                  ->nullable()
                  ->after('status');
            $table->foreign('account_manager_id')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
            $table->index('account_manager_id', 'idx_clients_am');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['account_manager_id']);
            $table->dropIndex('idx_clients_am');
            $table->dropColumn('account_manager_id');
        });
    }
};