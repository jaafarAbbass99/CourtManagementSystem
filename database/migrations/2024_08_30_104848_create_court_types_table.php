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
        Schema::create('court_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_id')->constrained('courts')->onDelete('cascade'); 
            $table->string('type');
            $table->string('city');
            $table->string('type_form');
            $table->tinyInteger('rank');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_types');
    }
};
