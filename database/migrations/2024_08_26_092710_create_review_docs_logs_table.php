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
        Schema::create('review_docs_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id'); 
            $table->unsignedBigInteger('doc_id'); 
            $table->enum('status',['true','false']);
            $table->text('review_comments')->nullable();
            
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doc_id')->references('id')->on('documents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_docs_logs');
    }
};
