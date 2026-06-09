<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Step 1: Rename columns
        Schema::table('leads', function (Blueprint $table) {
            $table->renameColumn('name', 'contact_name');
            $table->renameColumn('email', 'contact_email');
            $table->renameColumn('whatsapp', 'contact_phone');
            $table->renameColumn('website', 'website_url');
        });

        // Step 2: Relax NOT NULL on email & phone (fix existing bug —
        // controller validation says nullable, DB said NOT NULL)
        Schema::table('leads', function (Blueprint $table) {
            $table->string('contact_email', 255)->nullable()->change();
            $table->string('contact_phone', 255)->nullable()->change();
            // contact_name tetap NOT NULL (orang wajib ada)
        });

        // Step 3: Rename index leads_email_index → leads_contact_email_index
        Schema::table('leads', function (Blueprint $table) {
            $table->renameIndex('leads_email_index', 'leads_contact_email_index');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->renameIndex('leads_contact_email_index', 'leads_email_index');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->string('contact_email', 255)->nullable(false)->change();
            $table->string('contact_phone', 255)->nullable(false)->change();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->renameColumn('contact_name', 'name');
            $table->renameColumn('contact_email', 'email');
            $table->renameColumn('contact_phone', 'whatsapp');
            $table->renameColumn('website_url', 'website');
        });
    }
};
