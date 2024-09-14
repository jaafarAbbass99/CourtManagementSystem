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
        Schema::create('judgement_docs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('decision_id')->constrained('decisions')->onDelete('cascade');
            $table->foreignId('case_doc_id')->constrained('case_docs')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judgement_docs');
    }
};
