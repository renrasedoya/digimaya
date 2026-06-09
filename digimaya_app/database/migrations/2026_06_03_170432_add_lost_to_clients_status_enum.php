<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'lost' to the status enum. Raw ALTER avoids needing doctrine/dbal
        // (not available on shared hosting). Order kept logical.
        DB::statement("ALTER TABLE clients MODIFY COLUMN status ENUM('prospect','active','inactive','churned','lost') NOT NULL DEFAULT 'prospect'");
    }

    public function down(): void
    {
        // Revert: any 'lost' rows must be remapped first or this will truncate.
        DB::statement("UPDATE clients SET status = 'inactive' WHERE status = 'lost'");
        DB::statement("ALTER TABLE clients MODIFY COLUMN status ENUM('prospect','active','inactive','churned') NOT NULL DEFAULT 'prospect'");
    }
};