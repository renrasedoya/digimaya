<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('client_id')
                ->nullable()
                ->constrained('clients')
                ->nullOnDelete();

            $table->foreignId('service_id')
                ->nullable()
                ->constrained('services')
                ->nullOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Denormalized for fast reporting
            $table->enum('source_category', ['agency', 'academy', 'other']);

            // Core fields
            $table->decimal('amount', 15, 2);
            $table->date('received_date');
            $table->enum('payment_method', [
                'bank_transfer',
                'cash',
                'qris',
                'credit_card',
                'other',
            ])->default('bank_transfer');

            // Reference / description
            $table->string('reference_number')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('received_date');
            $table->index('source_category');
            $table->index(['source_category', 'received_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
