<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('name', 'business_name');
            $table->renameColumn('primary_contact_name', 'contact_name');
            $table->renameColumn('primary_contact_email', 'contact_email');
            $table->renameColumn('primary_contact_phone', 'contact_phone');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('business_name', 'name');
            $table->renameColumn('contact_name', 'primary_contact_name');
            $table->renameColumn('contact_email', 'primary_contact_email');
            $table->renameColumn('contact_phone', 'primary_contact_phone');
        });
    }
};