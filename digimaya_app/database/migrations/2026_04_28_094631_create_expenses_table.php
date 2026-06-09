<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('expense_category_id')
                ->constrained('expense_categories')
                ->restrictOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Core fields
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->string('vendor_name')->nullable();
            $table->enum('payment_method', [
                'bank_transfer',
                'cash',
                'qris',
                'credit_card',
                'other',
            ])->default('bank_transfer');

            // Recurring tracking
            $table->enum('recurring_type', [
                'one_time',
                'monthly',
                'yearly',
            ])->default('one_time');

            // Reference / description
            $table->string('reference_number')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('expense_date');
            $table->index('expense_category_id');
            $table->index(['expense_category_id', 'expense_date']);
            $table->index('recurring_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
