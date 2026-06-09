<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_tiers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('budget');       // monthly ad budget (Rp)
            $table->unsignedBigInteger('agency_fee');    // monthly agency fee (Rp)
            $table->enum('zone', ['lower', 'upper']);    // lower = 4-10jt, upper = 11jt+
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_tiers');
    }
};
