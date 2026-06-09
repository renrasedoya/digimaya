<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();

            // Basic info
            $table->string('name');
            $table->string('logo_path')->nullable();
            $table->string('website_url')->nullable();
            $table->string('industry')->nullable();

            // CRM status
            $table->enum('status', ['prospect', 'active', 'inactive', 'churned'])->default('prospect');
            $table->date('client_since')->nullable();
            $table->date('client_until')->nullable();

            // Contact
            $table->string('primary_contact_name')->nullable();
            $table->string('primary_contact_email')->nullable();
            $table->string('primary_contact_phone')->nullable();

            // Financial (for ROI calculation)
            $table->decimal('monthly_retainer', 12, 2)->nullable();
            $table->decimal('acquisition_cost', 12, 2)->nullable();

            // Meta
            $table->string('source')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('show_in_logo_grid')->default(true);
            $table->integer('sort_order')->default(0);

            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for common queries
            $table->index('status');
            $table->index('show_in_logo_grid');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
