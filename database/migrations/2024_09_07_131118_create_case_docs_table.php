<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void { 
        Schema::create('case_docs', function (Blueprint $table) {
            $table->id(); 
            $table->string('summary'); 
            $table->enum('type', ['الحكم', 'الادلة', 'الدعوى']); 
            $table->foreignId('court_type_id')->constrained('court_types')->onDelete('cascade'); 
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade'); 
            $table->foreignId('doc_id')->constrained('documents')->onDelete('cascade'); 
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->timestamps(); 
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_docs');
    }
};
