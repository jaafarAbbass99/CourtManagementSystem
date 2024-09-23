<?php

use App\Enums\Party;
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
        Schema::create('interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade'); 
            $table->enum('party', [Party::PARTY_ONE->value,Party::PARTY_TWO->value]);
            $table->boolean('is_admin')
              ->default(false);

            $table->timestamps();

            $table->unique(['user_id' , 'case_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interests');
    }
};
