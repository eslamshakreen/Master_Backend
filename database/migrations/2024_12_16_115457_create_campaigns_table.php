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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');    // عنوان الحملة
            $table->string('subject');  // موضوع البريد
            $table->text('body');       // محتوى الرسالة (HTML/Text)
            $table->string('attachment')->nullable(); // مسار الملف المرفق (اختياري)
            $table->string('target')->default('leads');
            // target: leads, students, or maybe both
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
