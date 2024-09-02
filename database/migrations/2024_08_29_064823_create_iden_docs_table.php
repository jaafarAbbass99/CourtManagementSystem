<?php

use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iden_docs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doc_id'); 
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('req_doc_id'); 

            $table->unsignedTinyInteger('status')
                ->default(Status::PENDING->value);
            
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doc_id')->references('id')->on('documents')->onDelete('cascade');
            $table->foreign('req_doc_id')->references('id')->on('required_ide_docs')->onDelete('cascade');

            $table->unique(['user_id', 'req_doc_id']);
            $table->unique(['doc_id']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iden_docs');
    }
};
