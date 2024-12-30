<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_testimonials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');

            $table->string('name');       // اسم الشخص الذي يقدّم الـ testimonial
            $table->string('job')->nullable(); // وظيفته أو منصبه
            $table->text('description');  // نص الشهادة أو الرأي
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_testimonials');
    }
};
