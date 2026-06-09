<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin','marketing','account_manager','advertiser') NOT NULL");

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_am_id')->nullable()->after('role');
            $table->foreign('parent_am_id')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_am_id']);
            $table->dropColumn('parent_am_id');
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin','marketing') NOT NULL");
    }
};