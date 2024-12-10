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
        Schema::create('heroes', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();        // عنوان الصورة (اختياري)
            $table->text('description')->nullable();    // وصف الصورة
            $table->string('image')->nullable();        // مسار الصورة
            $table->integer('order')->default(0);
            $table->string('type')->nullable();       // ترتيب العرض، يمكنك استخدامه لفرز الصور
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heroes');
    }
};
