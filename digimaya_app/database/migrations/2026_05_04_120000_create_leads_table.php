<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            // Capture data dari form (atau manual input by marketing)
            $table->string('name');
            $table->string('email');
            $table->string('whatsapp');
            $table->string('business_name')->nullable();
            $table->string('website')->nullable();
            $table->string('monthly_ad_budget', 20)->nullable(); // '<5jt', '5-10jt', '10-25jt', '25-50jt', '>50jt'
            $table->text('message')->nullable();

            // Marketing tracking
            $table->string('source', 50)->default('contact_form');
            // values: contact_form, whatsapp, meta_ads, google_ads, referral, manual, other
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            $table->string('referrer_url', 500)->nullable();

            // Status workflow (marketing phase)
            $table->string('status', 32)->default('new');
            // values: new, contacted, screened, promoted, disqualified

            // Assignment
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Promotion tracking (Lead → Client)
            $table->dateTime('promoted_at')->nullable();
            $table->foreignId('promoted_to_client_id')
                ->nullable()
                ->constrained('clients')
                ->nullOnDelete();

            // Disqualification tracking
            $table->dateTime('disqualified_at')->nullable();
            $table->text('disqualification_reason')->nullable();

            // Activity tracking (timestamps for first/last contact)
            $table->dateTime('first_contacted_at')->nullable();
            $table->dateTime('last_contacted_at')->nullable();

            // Audit
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('source');
            $table->index('email');
            $table->index('assigned_to');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
