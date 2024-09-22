<?php

use App\Enums\Status;
use App\Enums\StatusOrder;
use App\Enums\TypeOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('decision_orders', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('decision_id'); 
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedTinyInteger('type_order');
            $table->unsignedTinyInteger('status_order')->default(Status::PENDING->value); 
            $table->date('response_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('decision_id')->references('id')->on('decisions')->onDelete('cascade');
 
            $table->unique(['decision_id']);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decision_orders');
    }
};
