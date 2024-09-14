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
        Schema::create('wins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_type_id')->constrained('court_types')->onDelete('cascade');
            $table->foreignId('attorney_id')->constrained('power_of_attorneys')->onDelete('cascade');
            $table->enum('get',['yes','no','yet']);
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wins');
    }
};
