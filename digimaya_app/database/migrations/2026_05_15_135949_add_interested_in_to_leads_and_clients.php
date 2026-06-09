<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->enum('interested_in', ['agency', 'academy', 'partnership', 'others'])
                  ->nullable()
                  ->after('source');
            $table->string('interested_in_other', 255)
                  ->nullable()
                  ->after('interested_in');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->enum('interested_in', ['agency', 'academy', 'partnership', 'others'])
                  ->nullable()
                  ->after('source');
            $table->string('interested_in_other', 255)
                  ->nullable()
                  ->after('interested_in');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['interested_in', 'interested_in_other']);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['interested_in', 'interested_in_other']);
        });
    }
};
