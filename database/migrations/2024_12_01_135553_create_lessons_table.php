<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url')->nullable(); // إذا كان الدرس يحتوي على فيديو مستقل
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('lesson_order')->default(0);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
