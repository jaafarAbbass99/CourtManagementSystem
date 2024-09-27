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
        Schema::create('dewan_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dewan_id')->constrained('dewans')->onDelete('cascade');
            $table->foreignId('decision_order_id')->constrained('decision_orders')->onDelete('cascade');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dewan_orders');
    }
};
