<?php

use App\Enums\Representing;
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
        Schema::create('decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('sessions')->onDelete('cascade'); 
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade'); 
            
            $table->text('summary'); 
            $table->enum('status', ['ابتدائي', 'نهائي', 'مستأنف','منقوض', 'مؤجل' , "تحت التنفيذ", "معلق التنفيذ"]); 
            $table->enum('favor', [Representing::PARTY_ONE->value,Representing::PARTY_TWO->value , Representing::NOBODY->value]);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decisions');
    }
};
