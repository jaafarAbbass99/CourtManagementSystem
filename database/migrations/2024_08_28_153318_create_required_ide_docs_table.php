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
        Schema::create('required_ide_docs', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('req_doc');
            $table->unsignedTinyInteger('for');

            $table->unique(['req_doc', 'for']);
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('required_ide_docs');
    }
};
