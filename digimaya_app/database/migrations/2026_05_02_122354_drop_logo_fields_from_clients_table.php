<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'show_in_logo_grid', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('logo_path')->nullable();
            $table->boolean('show_in_logo_grid')->default(false);
            $table->integer('sort_order')->default(0);
        });
    }
};
