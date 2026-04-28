<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->integer('marks')->default(0);
            $table->string('grade')->default('N/A'); // A, B, C, Fail
            $table->timestamps();
            $table->softDeletes(); // For soft delete functionality
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
