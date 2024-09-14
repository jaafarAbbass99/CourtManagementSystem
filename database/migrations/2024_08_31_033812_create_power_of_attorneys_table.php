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
        Schema::create('power_of_attorneys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id'); 
            $table->unsignedBigInteger('lawyerCourt_id'); 
            $table->enum('representing', ['الطرف الاول', 'الطرف الثاني']);
           

            $table->timestamps();

            $table->unique(['case_id','lawyerCourt_id']);
            // العلاقات
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
            $table->foreign('lawyerCourt_id')->references('id')->on('lawyer_courts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('power_of_attorneys');
    }
};
