<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_study_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_study_id')->constrained('case_studies')->cascadeOnDelete();
            $table->string('value');
            $table->string('label');
            $table->integer('position_order')->default(0);
            $table->timestamps();

            $table->index(['case_study_id', 'position_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_study_results');
    }
};
