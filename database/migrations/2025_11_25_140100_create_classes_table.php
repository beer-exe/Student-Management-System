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
        Schema::create('classes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->id();
            $table->string('name');
            $table->string('year')->nullable();
            
            $table->foreignId('grade_id')->constrained()->onDelete('cascade');
            
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            
            $table->foreignId('subject_stream_id')->nullable()->constrained()->nullOnDelete();
            
            $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
