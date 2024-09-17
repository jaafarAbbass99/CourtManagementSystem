<?php

use App\Enums\SessionStatus;
use App\Enums\SessionType;
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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_judge_id')->constrained('case_judges')->onDelete('cascade');
            $table->integer('session_number')->default(0);
            $table->date('session_date'); 
            $table->time('session_time'); 
            $table->unsignedTinyInteger('session_type')
              ->default(SessionType ::PRELIMINARY->value);
            $table->unsignedTinyInteger('session_status')
              ->default(SessionStatus::scheduled->value);

            $table->unique(['case_judge_id','session_date','session_time']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
