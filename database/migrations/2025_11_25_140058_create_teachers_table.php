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
        Schema::create('teachers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('initials')->nullable();
            $table->string('salutation')->nullable();
            $table->string('nic')->nullable();
            $table->date('dob')->nullable();
            

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->foreignId('subject_stream_id')->nullable()->constrained()->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
