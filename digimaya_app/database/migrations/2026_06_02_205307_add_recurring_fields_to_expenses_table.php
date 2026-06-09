<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->enum('status', ['draft', 'confirmed', 'skipped'])
                ->default('confirmed')
                ->after('recurring_type');

            $table->unsignedBigInteger('recurring_parent_id')
                ->nullable()
                ->after('status');

            $table->date('recurring_until')
                ->nullable()
                ->after('recurring_parent_id');

            $table->foreign('recurring_parent_id')
                ->references('id')
                ->on('expenses')
                ->nullOnDelete();

            $table->index('recurring_parent_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['recurring_parent_id']);
            $table->dropIndex(['recurring_parent_id']);
            $table->dropIndex(['status']);
            $table->dropColumn(['status', 'recurring_parent_id', 'recurring_until']);
        });
    }
};