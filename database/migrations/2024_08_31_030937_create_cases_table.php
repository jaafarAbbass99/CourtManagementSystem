<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('case_number')->autoIncrement()->unsigned()->unique();
            $table->bigInteger('number');
            $table->string('full_number')->unique();
            $table->string('party_one');
            $table->string('party_two');
            $table->text('subject');
            $table->unsignedBigInteger('court_id');
            $table->unsignedBigInteger('case_type_id');
            $table->timestamps();

            $table->foreign('court_id')->references('id')->on('courts');
            $table->foreign('case_type_id')->references('id')->on('case_types');
            
            $table->unique(['number','case_type_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
