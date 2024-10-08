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
        Schema::create('dewans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade'); 
            $table->foreignId('court_type_id')->constrained('court_types')->onDelete('cascade'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dewans');
    }
};
