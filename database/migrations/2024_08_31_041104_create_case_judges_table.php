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
        Schema::create('case_judges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade'); 
            $table->foreignId('judge_section_id')->constrained('judge_sections')->onDelete('cascade');
            $table->enum('status', ['resolved', 'unresolved'])->default('unresolved'); 

            $table->bigInteger('base_number')->unique();
            $table->string('full_number')->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_judges');
    }
};
